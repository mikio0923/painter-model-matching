<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobOffer;
use App\Models\Message;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * 動作確認用のデモデータを大量に投入する。
 *
 * - 画家 10 + モデル 10（パスワードは全員 "password"）
 *   email は painter1@demo.local / model1@demo.local の連番
 * - 各画家に 2〜3 件の依頼
 * - 一部に応募・受諾・取引完了・レビュー
 * - 一部の組み合わせで 5〜25 通のメッセージ（多数会話の検証用）
 * - 個別依頼 (pending / accepted / declined)
 *
 * 実行:
 *   docker compose exec app php artisan db:seed --class=DemoSeeder
 *
 * 再投入したい場合は事前に
 *   docker compose exec app php artisan migrate:fresh
 * を実行することで全テーブルが空になる（既存データを残したまま
 * 追加実行する場合、demo.local の email は重複して落ちるので注意）。
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 既存 demo データをクリーンアップ（冪等化）
        // User は SoftDeletes が有効なので forceDelete() で物理削除する
        // demo.local の画家が作った Job を先に消すことで、関連する応募・メッセージ・
        // 個別依頼・レビューも cascadeOnDelete で連鎖削除される
        $this->command->info('既存の demo データを削除...');
        $demoUserIds = User::withTrashed()->where('email', 'like', '%@demo.local')->pluck('id');
        if ($demoUserIds->isNotEmpty()) {
            Job::whereIn('painter_id', $demoUserIds)->delete();
            User::withTrashed()->whereIn('id', $demoUserIds)->forceDelete();
        }

        $this->command->info('画家とモデルを作成...');

        $painters = collect();
        for ($i = 1; $i <= 10; $i++) {
            $u = User::create([
                'name'              => "画家{$i}号",
                'email'             => "painter{$i}@demo.local",
                'password'          => Hash::make('password'),
                'role'              => 'painter',
                'email_verified_at' => now(),
            ]);
            PainterProfile::create([
                'user_id'         => $u->id,
                'display_name'    => "画家{$i}号",
                'bio'             => "デモ用の画家プロフィール #{$i}。風景画から人物画まで幅広く描きます。",
                'years_active'    => 1 + ($i % 15),
                'gender'          => $i % 2 === 0 ? '男性' : '女性',
                'prefecture'      => '東京都',
                'accepts_offers'  => true,
                'art_styles'      => ['油絵', '水彩'],
                'specialties'     => ['ポートレート'],
                'activity_regions'=> ['東京都', '神奈川県'],
            ]);
            $painters->push($u);
        }

        $models = collect();
        for ($i = 1; $i <= 10; $i++) {
            $u = User::create([
                'name'              => "モデル{$i}号",
                'email'             => "model{$i}@demo.local",
                'password'          => Hash::make('password'),
                'role'              => 'model',
                'email_verified_at' => now(),
            ]);
            ModelProfile::create([
                'user_id'         => $u->id,
                'display_name'    => "モデル{$i}号",
                'bio'             => "デモ用のモデルプロフィール #{$i}。撮影経験あり、ご相談歓迎です。",
                'gender'          => $i % 3 === 0 ? '男性' : '女性',
                'age'             => 20 + ($i % 12),
                'prefecture'      => '東京都',
                'is_public'       => true,
                'online_available'=> $i % 2 === 0,
                'height'          => 155 + $i,
                'style_tags'      => $i % 2 === 0 ? ['清楚', 'カジュアル'] : ['クール', 'モード'],
            ]);
            $models->push($u);
        }

        $this->command->info('依頼を作成...');
        $jobs = collect();
        foreach ($painters as $idx => $painter) {
            $count = 2 + ($idx % 2); // 2〜3 件
            for ($j = 1; $j <= $count; $j++) {
                $jobs->push(Job::create([
                    'painter_id'      => $painter->id,
                    'title'           => "{$painter->name} の依頼 #{$j}",
                    'description'     => "ポートレート撮影の依頼です。\n撮影スタイル: " . ($j % 2 === 0 ? 'スタジオ' : '屋外'),
                    'reward_amount'   => (5 + ($j * 3)) * 1000,
                    'reward_unit'     => 'per_session',
                    'location_type'   => $j % 2 === 0 ? 'online' : 'offline',
                    'prefecture'      => '東京都',
                    'status'          => 'open',
                    'scheduled_date'  => now()->addDays(7 + $j * 3),
                    'apply_deadline'  => now()->addDays(3 + $j),
                    'recruitment_number' => 1,
                ]));
            }
        }

        $this->command->info('応募を作成...');
        $applications = collect();
        foreach ($jobs as $idx => $job) {
            // 60% の依頼に 1〜3 件の応募
            if ($idx % 5 === 4) continue; // 一部は応募ゼロ
            $applicants = $models->shuffle()->take(1 + ($idx % 3));
            foreach ($applicants as $m) {
                $applications->push(JobApplication::create([
                    'job_id'   => $job->id,
                    'model_id' => $m->id,
                    'message'  => "応募します。よろしくお願いいたします。",
                    'status'   => 'pending',
                ]));
            }
        }

        $this->command->info('一部応募を accept + 取引完了に...');
        $applications->take(5)->each(function (JobApplication $app) {
            $app->update([
                'status'              => 'accepted',
                'payment_received_at' => now()->subDay(),
            ]);
            $app->job->update(['status' => 'closed']);
        });

        $this->command->info('メッセージを大量投入（多数会話の検証用）...');
        $sampleMessages = [
            'お疲れ様です。撮影の件、よろしくお願いします。',
            '了解しました。スケジュール調整します。',
            '当日のロケーションについてご相談したいです。',
            '画像確認しました。とても素敵に撮れています！',
            'ありがとうございます。次回もよろしくお願いします。',
            '衣装は自前でも大丈夫でしょうか？',
            'はい、大丈夫です。当日は私服でお越しください。',
            '駅から徒歩何分くらいでしょうか？',
            '駅から徒歩5分ほどです。地図お送りしますね。',
            '了解しました。当日楽しみにしています。',
        ];

        // 受諾済 5件のスレッドにそれぞれ多めのメッセージ
        $applications->take(5)->each(function (JobApplication $app) use ($sampleMessages) {
            $painterId = $app->job->painter_id;
            $modelId   = $app->model_id;
            $count     = 10 + ($app->id % 16); // 10〜25 通
            for ($k = 0; $k < $count; $k++) {
                Message::create([
                    'job_id'      => $app->job_id,
                    'sender_id'   => $k % 2 === 0 ? $modelId : $painterId,
                    'receiver_id' => $k % 2 === 0 ? $painterId : $modelId,
                    'body'        => $sampleMessages[$k % count($sampleMessages)],
                    'created_at'  => now()->subMinutes(($count - $k) * 5),
                    'updated_at'  => now()->subMinutes(($count - $k) * 5),
                    'read_at'     => $k < $count - 3 ? now()->subMinutes(($count - $k) * 4) : null,
                ]);
            }
        });

        // それ以外のスレッドにも軽めのメッセージ
        $applications->slice(5, 10)->each(function (JobApplication $app) use ($sampleMessages) {
            $painterId = $app->job->painter_id;
            $modelId   = $app->model_id;
            $count = 3 + ($app->id % 5);
            for ($k = 0; $k < $count; $k++) {
                Message::create([
                    'job_id'      => $app->job_id,
                    'sender_id'   => $k % 2 === 0 ? $modelId : $painterId,
                    'receiver_id' => $k % 2 === 0 ? $painterId : $modelId,
                    'body'        => $sampleMessages[($k + 2) % count($sampleMessages)],
                    'created_at'  => now()->subHours($count - $k),
                    'updated_at'  => now()->subHours($count - $k),
                ]);
            }
        });

        $this->command->info('個別依頼 (job_offers) を作成...');
        $statuses = [JobOffer::STATUS_PENDING, JobOffer::STATUS_ACCEPTED, JobOffer::STATUS_DECLINED];
        foreach ($painters->take(6) as $painter) {
            $painterJobs = $jobs->where('painter_id', $painter->id)->values();
            if ($painterJobs->isEmpty()) continue;

            // pending な offer 用に開いた依頼を使う
            $openJob = $painterJobs->first();
            $usedModelIds = [];
            for ($k = 0; $k < 3; $k++) {
                $target = $models->shuffle()->first(fn($m) => !in_array($m->id, $usedModelIds));
                if (!$target) break;
                $usedModelIds[] = $target->id;

                $status = $statuses[$k % 3];
                JobOffer::create([
                    'job_id'          => $openJob->id,
                    'model_id'        => $target->id,
                    'painter_message' => "{$target->name} さんへ。個別に撮影をお願いしたく、ご検討いただけますと幸いです。",
                    'status'          => $status,
                    'model_response'  => $status !== JobOffer::STATUS_PENDING
                        ? ($status === JobOffer::STATUS_ACCEPTED ? '喜んで受諾します。' : '今回は見送らせてください。')
                        : null,
                    'responded_at'    => $status !== JobOffer::STATUS_PENDING ? now()->subHours(2) : null,
                ]);

                // accepted offer は対応する JobApplication も作成（既存応募と統合）
                if ($status === JobOffer::STATUS_ACCEPTED) {
                    JobApplication::firstOrCreate(
                        ['job_id' => $openJob->id, 'model_id' => $target->id],
                        [
                            'message'             => '個別依頼を受諾',
                            'status'              => 'accepted',
                            'payment_received_at' => now()->subHours(1),
                        ]
                    );
                }
            }
        }

        $this->command->info('レビューを作成...');
        $applications->filter(fn(JobApplication $a) => $a->payment_received_at !== null)
            ->take(4)
            ->each(function (JobApplication $app) {
                // 画家 → モデル
                Review::firstOrCreate([
                    'job_id'           => $app->job_id,
                    'reviewer_id'      => $app->job->painter_id,
                    'reviewed_user_id' => $app->model_id,
                ], [
                    'rating'  => 3 + ($app->id % 3), // 3〜5
                    'comment' => '丁寧で素敵な撮影でした。また是非お願いしたいです。',
                ]);
                // モデル → 画家
                Review::firstOrCreate([
                    'job_id'           => $app->job_id,
                    'reviewer_id'      => $app->model_id,
                    'reviewed_user_id' => $app->job->painter_id,
                ], [
                    'rating'  => 3 + (($app->id + 1) % 3),
                    'comment' => '丁寧な進行で安心して撮影できました。',
                ]);
            });

        // ── painter10 専用: 過去の完了依頼を 4 件 + 各々レビュー ──
        // 画家プロフィール画面の「これまでの実績」「レビュー」セクションの動作確認用
        $this->command->info('painter10 の過去完了依頼を作成...');
        $painter10 = $painters->last();
        $pastTitles = [
            ['title' => 'ポートレート撮影 / スタジオ',     'days' => 35, 'desc' => 'スタジオでのポートレート撮影。自然光メインで柔らかい雰囲気に仕上げました。'],
            ['title' => '屋外ロケーション撮影',             'days' => 55, 'desc' => '都内の公園での屋外撮影。夕方のマジックアワーを活用。'],
            ['title' => 'モード系作品撮り',                 'days' => 80, 'desc' => 'モード雑誌風の作品撮り。衣装はスタイリスト同行。'],
            ['title' => 'カジュアル日常スナップ',           'days' => 110,'desc' => '日常の延長線にあるような自然なスナップ撮影でした。'],
        ];
        foreach ($pastTitles as $idx => $info) {
            $pastJob = Job::create([
                'painter_id'      => $painter10->id,
                'title'           => "[{$painter10->name}] " . $info['title'],
                'description'     => $info['desc'],
                'reward_amount'   => 15000 + $idx * 5000,
                'reward_unit'     => 'per_session',
                'location_type'   => $idx % 2 === 0 ? 'offline' : 'online',
                'prefecture'      => '東京都',
                'status'          => 'closed',
                'scheduled_date'  => now()->subDays($info['days']),
                'apply_deadline'  => now()->subDays($info['days'] + 14),
                'recruitment_number' => 1,
            ]);

            // モデルを1人割り当てて取引完了 + 双方レビュー
            $partnerModel = $models[$idx % $models->count()];
            $pastApp = JobApplication::create([
                'job_id'              => $pastJob->id,
                'model_id'            => $partnerModel->id,
                'message'             => 'よろしくお願いいたします。',
                'status'              => 'accepted',
                'payment_received_at' => now()->subDays($info['days'] - 1),
            ]);

            // 画家(painter10) → モデル
            Review::create([
                'job_id'           => $pastJob->id,
                'reviewer_id'      => $painter10->id,
                'reviewed_user_id' => $partnerModel->id,
                'rating'           => 4 + ($idx % 2),
                'comment'          => 'プロ意識が高く、現場でも非常に協力的でした。',
            ]);
            // モデル → 画家(painter10)
            Review::create([
                'job_id'           => $pastJob->id,
                'reviewer_id'      => $partnerModel->id,
                'reviewed_user_id' => $painter10->id,
                'rating'           => 4 + (($idx + 1) % 2),
                'comment'          => 'ディレクションが明確で、安心して撮影に臨めました。',
            ]);
        }

        $this->command->info(sprintf(
            '完了: 画家 %d / モデル %d / 依頼 %d / 応募 %d / メッセージ %d / 個別依頼 %d / レビュー %d',
            User::where('role', 'painter')->where('email', 'like', '%@demo.local')->count(),
            User::where('role', 'model')->where('email', 'like', '%@demo.local')->count(),
            Job::count(),
            JobApplication::count(),
            Message::count(),
            JobOffer::count(),
            Review::count(),
        ));
        $this->command->info('ログイン: painter1@demo.local / model1@demo.local ... など。 password: password');
    }
}
