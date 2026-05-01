<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AccountDeletionService
{
    public const GRACE_PERIOD_DAYS = 30;

    /**
     * 退会処理を実行（ソフトデリート + 関連データ整理）
     */
    public function requestDeletion(User $user, ?string $reason, ?string $feedback): void
    {
        $this->validateCanDelete($user);

        DB::transaction(function () use ($user, $reason, $feedback) {
            // 進行中の依頼をクローズ
            if ($user->isPainter()) {
                $user->jobs()->where('status', 'open')->update(['status' => 'closed']);
            }

            // モデルプロフィールを非公開化
            if ($user->isModel() && $user->modelProfile) {
                $user->modelProfile->update(['is_public' => false]);
            }

            // お気に入り・通知を物理削除
            $user->favorites()->delete();
            $user->notifications()->delete();

            // 退会情報を記録
            $user->update([
                'deletion_requested_at' => now(),
                'deletion_reason' => $reason,
                'deletion_feedback' => $feedback,
            ]);

            // ソフトデリート
            $user->delete();
        });
    }

    /**
     * 退会できない条件をチェック
     */
    public function validateCanDelete(User $user): void
    {
        $errors = [];

        if ($user->isPainter()) {
            // 承認済みで未完了の応募がある依頼があるか
            $hasActiveAcceptedJobs = $user->jobs()
                ->whereIn('status', ['open', 'closed'])
                ->whereHas('applications', fn ($q) => $q->where('status', 'accepted'))
                ->exists();

            if ($hasActiveAcceptedJobs) {
                $errors[] = '進行中の依頼があるため退会できません。先に依頼を完了またはクローズしてください。';
            }
        }

        if ($user->isModel()) {
            // 承認済みで完了していない応募があるか
            $hasActiveAcceptedApplications = $user->jobApplications()
                ->where('status', 'accepted')
                ->whereHas('job', fn ($q) => $q->whereIn('status', ['open', 'closed']))
                ->exists();

            if ($hasActiveAcceptedApplications) {
                $errors[] = '承認済みで未完了の応募があるため退会できません。依頼の完了をお待ちください。';
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages(['account' => $errors]);
        }
    }

    /**
     * 退会から猶予期間が経過したユーザーを匿名化
     */
    public function anonymizeExpiredUsers(): int
    {
        $count = 0;

        User::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays(self::GRACE_PERIOD_DAYS))
            ->whereNull('anonymized_at')
            ->chunk(100, function ($users) use (&$count) {
                foreach ($users as $user) {
                    $this->anonymizeUser($user);
                    $count++;
                }
            });

        return $count;
    }

    /**
     * 個別ユーザーの匿名化処理
     */
    public function anonymizeUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            // プロフィール画像をストレージから削除
            if ($user->modelProfile) {
                if ($user->modelProfile->profile_image_path) {
                    Storage::disk('public')->delete($user->modelProfile->profile_image_path);
                }
                foreach ($user->modelProfile->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $user->modelProfile->delete();
            }

            if ($user->painterProfile) {
                if ($user->painterProfile->profile_image_path) {
                    Storage::disk('public')->delete($user->painterProfile->profile_image_path);
                }
                $user->painterProfile->delete();
            }

            // ユーザーデータを匿名化
            $user->forceFill([
                'name' => '退会済みユーザー',
                'email' => 'deleted_' . $user->id . '_' . hash('sha256', $user->email) . '@deleted.local',
                'phone_number_part1' => null,
                'phone_number_part2' => null,
                'phone_number_part3' => null,
                'postal_code_part1' => null,
                'postal_code_part2' => null,
                'prefecture' => null,
                'city' => null,
                'street_number' => null,
                'building_name' => null,
                'remember_token' => null,
                'anonymized_at' => now(),
            ])->save();
        });
    }
}
