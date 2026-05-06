<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\IdentityVerification;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\Message;
use App\Models\ModelProfile;
use App\Models\Notification;
use App\Models\PainterProfile;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * 動作確認用のデモアカウント・取引履歴を一括投入するシーダー
 *
 * 流す方法:
 *   docker compose exec app php artisan db:seed --class=DemoAccountSeeder
 *
 * 何度実行しても整合性が取れるよう、既存の demo* データは削除してから再生成します。
 */
class DemoAccountSeeder extends Seeder
{
    private const PAINTER_EMAIL = 'demo.painter@example.com';
    private const MODEL_EMAIL   = 'demo.model@example.com';
    private const PASSWORD      = 'password';

    public function run(): void
    {
        DB::transaction(function () {
            // 1) 既存のデモデータをクリーンアップ
            $this->cleanup();

            // 2) アカウント・プロフィール作成
            $painter = $this->createPainter();
            $model   = $this->createModel();

            // 3) 本人確認（モデルのみ承認済み）
            $this->createIdentityVerification($model);

            // 4) 取引データ作成
            //    Job 1: 完了済み（レビュー双方向）
            //    Job 2: 進行中（accepted・メッセージあり）
            //    Job 3: 公開中（demo painter から募集中、別モデルから応募）
            $job1 = $this->createCompletedJob($painter, $model);
            $job2 = $this->createOngoingJob($painter, $model);
            $job3 = $this->createOpenJob($painter);

            // 5) 別の画家依頼に demo モデルが応募
            $this->createOutgoingApplication($model);

            // 6) お気に入り
            $this->createFavorites($painter, $model);

            // 7) 通知（実利用時に近づける）
            $this->createNotifications($painter, $model, $job2);
        });

        $this->command->info('');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info(' ✓ デモアカウントを作成しました');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info(' [画家] ' . self::PAINTER_EMAIL . ' / ' . self::PASSWORD);
        $this->command->info(' [モデル] ' . self::MODEL_EMAIL . ' / ' . self::PASSWORD);
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }

    /**
     * 既存のデモデータを削除（FK の cascade / nullOnDelete で関連も整理される）
     */
    private function cleanup(): void
    {
        $emails = [self::PAINTER_EMAIL, self::MODEL_EMAIL];
        $userIds = User::whereIn('email', $emails)->pluck('id')->all();

        if (empty($userIds)) {
            return;
        }

        // 関連データを明示削除（cascade に頼らない安全策）
        $jobIds = Job::whereIn('painter_id', $userIds)->pluck('id')->all();
        if (!empty($jobIds)) {
            Message::whereIn('job_id', $jobIds)->delete();
            Review::whereIn('job_id', $jobIds)->delete();
            JobApplication::whereIn('job_id', $jobIds)->delete();
            Job::whereIn('id', $jobIds)->delete();
        }

        JobApplication::whereIn('model_id', $userIds)->delete();
        Message::whereIn('sender_id', $userIds)->orWhereIn('receiver_id', $userIds)->delete();
        Review::whereIn('reviewer_id', $userIds)->orWhereIn('reviewed_user_id', $userIds)->delete();
        Favorite::whereIn('user_id', $userIds)->delete();
        Notification::whereIn('user_id', $userIds)->delete();
        IdentityVerification::whereIn('user_id', $userIds)->delete();
        ModelProfile::whereIn('user_id', $userIds)->delete();
        PainterProfile::whereIn('user_id', $userIds)->delete();

        User::whereIn('id', $userIds)->forceDelete();
    }

    private function createPainter(): User
    {
        $user = User::create([
            'name'              => '高橋 蒼',
            'email'             => self::PAINTER_EMAIL,
            'password'          => Hash::make(self::PASSWORD),
            'role'              => 'painter',
            'email_verified_at' => now(),
            'phone_number_part1'=> '080',
            'phone_number_part2'=> '1234',
            'phone_number_part3'=> '5678',
            'postal_code_part1' => '150',
            'postal_code_part2' => '0001',
            'prefecture'        => '東京都',
            'city'              => '渋谷区',
            'street_number'     => '神宮前1-2-3',
            'building_name'     => 'アトリエ青山',
        ]);

        PainterProfile::create([
            'user_id'      => $user->id,
            'display_name' => '高橋 蒼',
            'gender'       => 'male',
            'prefecture'   => '東京都',
            'art_styles'   => ['油彩', 'デッサン', 'ポートレート'],
            'portfolio_url'=> 'https://example.com/takahashi-aoi',
        ]);

        return $user;
    }

    private function createModel(): User
    {
        $user = User::create([
            'name'              => '佐倉 由芽',
            'email'             => self::MODEL_EMAIL,
            'password'          => Hash::make(self::PASSWORD),
            'role'              => 'model',
            'email_verified_at' => now(),
            'phone_number_part1'=> '090',
            'phone_number_part2'=> '8765',
            'phone_number_part3'=> '4321',
            'postal_code_part1' => '154',
            'postal_code_part2' => '0024',
            'prefecture'        => '東京都',
            'city'              => '世田谷区',
            'street_number'     => '三軒茶屋2-15-7',
            'building_name'     => 'ハイツ世田谷 302',
        ]);

        ModelProfile::create([
            'user_id'           => $user->id,
            'display_name'      => '佐倉 由芽',
            'birthdate'         => Carbon::create(1998, 4, 12),
            'age'               => 27,
            'gender'            => 'female',
            'prefecture'        => '東京都',
            'activity_regions'  => ['東京都', '神奈川県'],
            'height'            => 162,
            'bust'              => 82,
            'waist'             => 60,
            'hip'               => 86,
            'shoe_size'         => '23.5',
            'clothing_size'     => 'M',
            'model_types'       => ['ポートレート', '人物画', '広告'],
            'body_type'         => 'スリム',
            'hair_type'         => 'long',
            'occupation'        => 'フリーランス・モデル',
            'hobbies'           => '美術館巡り、写真鑑賞',
            'bio'               => 'ポートレート・人物画を中心にモデル経験5年。和装・洋装どちらも対応可能で、自然光を活かした静謐な作品を得意としています。撮影現場では画家のディレクションに丁寧に応えることを心がけています。',
            'experience'        => '油彩・水彩・デッサン合わせて約30作品にモデルとして参加。グループ展のメインビジュアルを2度務めました。',
            'sns_links'         => ['https://www.instagram.com/sakura.yume.demo/'],
            'style_tags'        => ['ポートレート', '和装', 'クラシック', '自然光'],
            'pose_ranges'       => ['立ち', '座り', '横臥'],
            'avoid_work_types'  => ['露出度の高い衣装', '長期に渡る撮影'],
            'online_available'  => true,
            'reward_min'        => 6000,
            'reward_max'        => 12000,
            'is_public'         => true,
            'identity_verified' => true,
            'terms_text'        => '撮影前にコンセプト共有を希望。撮影データの私的利用（SNS投稿等）はご相談ください。',
        ]);

        return $user;
    }

    private function createIdentityVerification(User $model): void
    {
        IdentityVerification::create([
            'user_id'           => $model->id,
            'document_type'     => 'drivers_license',
            'front_image_path'  => 'identity/' . $model->id . '/dummy_front.jpg',
            'back_image_path'   => 'identity/' . $model->id . '/dummy_back.jpg',
            'status'            => 'approved',
            'reviewed_by'       => User::where('role', 'admin')->value('id'),
            'reviewed_at'       => now()->subDays(20),
            'created_at'        => now()->subDays(22),
            'updated_at'        => now()->subDays(20),
        ]);
    }

    /**
     * Job 1: 約45日前に作成 → 完了済み・双方向レビュー投稿済み
     */
    private function createCompletedJob(User $painter, User $model): Job
    {
        $createdAt = now()->subDays(45);
        $acceptedAt = now()->subDays(43);
        $completedAt = now()->subDays(20);

        $job = new Job([
            'painter_id'         => $painter->id,
            'title'              => '春の和装ポートレート — 桜の季節を描く',
            'description'        => "桜の季節をテーマにした和装ポートレートのモデルを募集します。\n撮影は3月下旬を予定。会場は青山のアトリエにて、約3時間の撮影を見込んでいます。\n撮影した写真をもとに油彩で仕上げる予定です。\n\n衣装はこちらでご用意しますが、お持ち込みも歓迎です。\n落ち着いた雰囲気で進めますので、初めての方もお気軽にどうぞ。",
            'usage_purpose'      => '油彩制作・個展出展',
            'category'           => 'ポートレート',
            'reward_amount'      => 15000,
            'reward_unit'        => 'per_session',
            'transportation_fee' => '実費支給',
            'costume_provided'   => 'あり（和装一式）',
            'target'             => '20〜30代女性',
            'recruitment_number' => 1,
            'location_type'      => 'offline',
            'prefecture'         => '東京都',
            'city'               => '港区',
            'address'            => '青山1-2-3 アトリエ青山',
            'access'             => '東京メトロ「青山一丁目駅」徒歩5分',
            'scheduled_date'     => now()->subDays(25),
            'apply_deadline'     => now()->subDays(35),
            'status'             => 'done',
        ]);
        $job->created_at = $createdAt;
        $job->updated_at = $completedAt;
        $job->save();

        // 応募
        $app = JobApplication::create([
            'job_id'    => $job->id,
            'model_id'  => $model->id,
            'message'   => '和装ポートレートの作品集を拝見し、ぜひ参加させていただきたく応募いたしました。3月下旬は調整可能です。よろしくお願いいたします。',
            'status'    => 'accepted',
            'created_at'=> $createdAt->copy()->addDays(1),
            'updated_at'=> $acceptedAt,
        ]);

        // メッセージ（撮影前の打ち合わせ → 当日 → 後日のお礼）
        $messages = [
            [$painter, $model, 'ご応募ありがとうございます。プロフィールと過去のお仕事を拝見し、ぜひお願いしたいと思いました。よろしくお願いいたします。', $acceptedAt->copy()->addHours(2)],
            [$model, $painter, 'こちらこそご縁をいただきありがとうございます。とても楽しみにしております。当日の集合時間と持ち物について改めてご連絡いただけますでしょうか。', $acceptedAt->copy()->addHours(5)],
            [$painter, $model, '3月29日（土）13時に青山アトリエで集合でお願いします。衣装はこちらで用意しますが、念のためメイク道具とヘアアレンジ用品をお持ちください。', $acceptedAt->copy()->addDays(1)],
            [$model, $painter, '承知しました。当日が楽しみです。撮影内容について、参考になるような作品やムードボードがあれば事前に共有いただけると嬉しいです。', $acceptedAt->copy()->addDays(1)->addHours(3)],
            [$painter, $model, 'ありがとうございます。先日制作した「春日和」シリーズの参考画像をいくつか送ります。落ち着いた表情と、視線をやや遠くに向ける構図を考えています。', $acceptedAt->copy()->addDays(2)],
            [$model, $painter, '画像拝見しました。とても素敵な雰囲気ですね。当日はこの空気感を意識して臨みます。', $acceptedAt->copy()->addDays(2)->addHours(2)],
            [$painter, $model, '本日はお疲れさまでした。とても良い表情を引き出していただき、撮影もスムーズに進めることができました。完成作品が楽しみです。', $completedAt->copy()->subDays(2)],
            [$model, $painter, 'こちらこそ、終始リラックスできる現場で楽しく撮影に臨めました。完成を心待ちにしております。', $completedAt->copy()->subDays(2)->addHours(4)],
            [$painter, $model, '先日完成した作品を個展で展示することになりました。詳細はメールでもお送りしますが、ぜひお越しいただけたら嬉しいです。', $completedAt->copy()->subDays(1)],
            [$model, $painter, 'おめでとうございます！必ず伺います。素晴らしい機会をご一緒できて光栄でした。', $completedAt],
        ];

        foreach ($messages as $idx => [$sender, $receiver, $body, $at]) {
            Message::create([
                'job_id'      => $job->id,
                'sender_id'   => $sender->id,
                'receiver_id' => $receiver->id,
                'body'        => $body,
                'read_at'     => $at->copy()->addMinutes(rand(5, 60)),
                'created_at'  => $at,
                'updated_at'  => $at,
            ]);
        }

        // 双方向レビュー
        Review::create([
            'job_id'           => $job->id,
            'reviewer_id'      => $painter->id,
            'reviewed_user_id' => $model->id,
            'rating'           => 'very_good',
            'comment'          => '事前準備から当日まで非常に丁寧で、表情の引き出しもプロフェッショナル。完成作品も期待以上のものになりました。また機会があればぜひお願いしたいモデルさんです。',
            'created_at'       => $completedAt->copy()->addDays(2),
            'updated_at'       => $completedAt->copy()->addDays(2),
        ]);
        Review::create([
            'job_id'           => $job->id,
            'reviewer_id'      => $model->id,
            'reviewed_user_id' => $painter->id,
            'rating'           => 'very_good',
            'comment'          => 'コンセプト共有から当日のディレクションまで丁寧に進めていただき、安心して撮影に臨めました。落ち着いた空気感の現場づくりが素晴らしかったです。',
            'created_at'       => $completedAt->copy()->addDays(3),
            'updated_at'       => $completedAt->copy()->addDays(3),
        ]);

        return $job;
    }

    /**
     * Job 2: 1週間前に作成 → 進行中（accepted・メッセージ4往復・撮影前）
     */
    private function createOngoingJob(User $painter, User $model): Job
    {
        $createdAt  = now()->subDays(7);
        $acceptedAt = now()->subDays(5);

        $job = new Job([
            'painter_id'         => $painter->id,
            'title'              => 'アトリエでの油彩ポートレート（自然光メイン）',
            'description'        => "アトリエの北窓から差し込む自然光を活かした油彩ポートレートのモデルを募集します。\n撮影時間は約4時間、その後の制作は5〜6回のポーズに分けて行う予定です。\n衣装は白〜オフホワイトのシンプルなものを想定しています。\n\nゆったりとしたペースで進めますので、長時間の撮影にも対応できる方を希望します。",
            'usage_purpose'      => '個人作品・ギャラリー展示用',
            'category'           => 'ポートレート',
            'reward_amount'      => 8000,
            'reward_unit'        => 'per_hour',
            'transportation_fee' => '実費支給',
            'costume_provided'   => 'なし（白系の衣装をお持ちください）',
            'target'             => '20代女性',
            'recruitment_number' => 1,
            'location_type'      => 'offline',
            'prefecture'         => '東京都',
            'city'               => '港区',
            'address'            => '青山1-2-3 アトリエ青山',
            'access'             => '東京メトロ「青山一丁目駅」徒歩5分',
            'scheduled_date'     => now()->addDays(10),
            'apply_deadline'     => now()->addDays(3),
            'status'             => 'open',
        ]);
        $job->created_at = $createdAt;
        $job->updated_at = $acceptedAt;
        $job->save();

        $app = JobApplication::create([
            'job_id'    => $job->id,
            'model_id'  => $model->id,
            'message'   => '前回の和装ポートレートでも大変お世話になりました。北窓の自然光を活かした作品の雰囲気がとても好きで、ぜひまたご一緒させていただきたく応募いたします。',
            'status'    => 'accepted',
            'created_at'=> $createdAt->copy()->addHours(8),
            'updated_at'=> $acceptedAt,
        ]);

        $messages = [
            [$painter, $model, '前回に引き続きありがとうございます。承認させていただきました。日程の件、来週の土日いずれかでご都合いかがでしょうか。', $acceptedAt->copy()],
            [$model, $painter, 'ありがとうございます！次の土曜日であれば終日空けることが可能です。', $acceptedAt->copy()->addHours(2)],
            [$painter, $model, 'では土曜日10時からアトリエにお越しいただけますか。今回はオフホワイトのシンプルなワンピース等をお持ちいただけると助かります。', $acceptedAt->copy()->addDays(1)],
            [$model, $painter, '承知しました。手持ちで条件に合うものをいくつか持参します。当日が楽しみです。', $acceptedAt->copy()->addDays(1)->addHours(3)],
        ];

        // 最後のメッセージは未読
        foreach ($messages as $idx => [$sender, $receiver, $body, $at]) {
            $isLast = ($idx === count($messages) - 1);
            Message::create([
                'job_id'      => $job->id,
                'sender_id'   => $sender->id,
                'receiver_id' => $receiver->id,
                'body'        => $body,
                'read_at'     => $isLast ? null : $at->copy()->addMinutes(rand(5, 60)),
                'created_at'  => $at,
                'updated_at'  => $at,
            ]);
        }

        return $job;
    }

    /**
     * Job 3: demo painter から募集中の依頼（demo モデル以外から応募あり）
     */
    private function createOpenJob(User $painter): Job
    {
        $createdAt = now()->subDays(3);

        $job = new Job([
            'painter_id'         => $painter->id,
            'title'              => '光と影をテーマにしたデッサン制作',
            'description'        => "コントラストの強い光と影を主題としたデッサン制作のモデルを募集します。\n表情よりもシルエット・骨格を活かしたポーズが中心となります。\n撮影は約3時間、その後制作に約3週間を見込んでいます。\n\n初回打ち合わせ可・モデル経験者歓迎。",
            'usage_purpose'      => 'デッサン教室での参考作品',
            'category'           => 'デッサン',
            'reward_amount'      => 7000,
            'reward_unit'        => 'per_hour',
            'transportation_fee' => '実費支給',
            'costume_provided'   => 'あり（複数パターン）',
            'target'             => '20〜40代男女',
            'recruitment_number' => 1,
            'location_type'      => 'offline',
            'prefecture'         => '東京都',
            'city'               => '港区',
            'address'            => '青山1-2-3 アトリエ青山',
            'access'             => '東京メトロ「青山一丁目駅」徒歩5分',
            'scheduled_date'     => now()->addDays(20),
            'apply_deadline'     => now()->addDays(14),
            'status'             => 'open',
        ]);
        $job->created_at = $createdAt;
        $job->updated_at = $createdAt;
        $job->save();

        // 別のモデルから応募（既存の seeded モデルがいる前提）
        $otherModels = User::where('role', 'model')
            ->where('email', '!=', self::MODEL_EMAIL)
            ->limit(2)
            ->get();

        foreach ($otherModels as $idx => $otherModel) {
            JobApplication::create([
                'job_id'    => $job->id,
                'model_id'  => $otherModel->id,
                'message'   => 'デッサンのモデル経験があります。ご検討よろしくお願いいたします。',
                'status'    => 'pending',
                'created_at'=> $createdAt->copy()->addDays($idx + 1),
                'updated_at'=> $createdAt->copy()->addDays($idx + 1),
            ]);
        }

        return $job;
    }

    /**
     * demo モデルが他の画家の依頼に応募（pending）
     */
    private function createOutgoingApplication(User $model): void
    {
        $otherJob = Job::where('status', 'open')
            ->where('painter_id', '!=', User::where('email', self::PAINTER_EMAIL)->value('id'))
            ->whereDoesntHave('applications', fn ($q) => $q->where('model_id', $model->id))
            ->first();

        if (!$otherJob) {
            return;
        }

        JobApplication::create([
            'job_id'   => $otherJob->id,
            'model_id' => $model->id,
            'message'  => 'プロフィールを拝見し、ぜひご一緒したいと思い応募いたします。日程は柔軟に調整可能ですのでお気軽にお声がけください。',
            'status'   => 'pending',
            'created_at'=> now()->subDays(2),
            'updated_at'=> now()->subDays(2),
        ]);
    }

    private function createFavorites(User $painter, User $model): void
    {
        $painterProfile = $painter->painterProfile;
        $modelProfile = $model->modelProfile;

        // 画家 → デモモデルをお気に入り
        Favorite::create([
            'user_id'          => $painter->id,
            'favoritable_type' => ModelProfile::class,
            'favoritable_id'   => $modelProfile->id,
            'created_at'       => now()->subDays(40),
            'updated_at'       => now()->subDays(40),
        ]);

        // 画家 → 他のモデル数名にもお気に入り
        $otherModels = ModelProfile::where('user_id', '!=', $model->id)
            ->where('is_public', true)
            ->limit(3)
            ->get();
        foreach ($otherModels as $idx => $otherModel) {
            Favorite::create([
                'user_id'          => $painter->id,
                'favoritable_type' => ModelProfile::class,
                'favoritable_id'   => $otherModel->id,
                'created_at'       => now()->subDays(15 - $idx),
                'updated_at'       => now()->subDays(15 - $idx),
            ]);
        }

        // モデル → 他の画家の依頼にお気に入り
        $otherJobs = Job::where('status', 'open')
            ->where('painter_id', '!=', $painter->id)
            ->limit(3)
            ->get();
        foreach ($otherJobs as $idx => $otherJob) {
            Favorite::create([
                'user_id'          => $model->id,
                'favoritable_type' => Job::class,
                'favoritable_id'   => $otherJob->id,
                'created_at'       => now()->subDays(8 - $idx),
                'updated_at'       => now()->subDays(8 - $idx),
            ]);
        }
    }

    private function createNotifications(User $painter, User $model, Job $ongoingJob): void
    {
        // モデル宛: 進行中ジョブのメッセージ未読通知
        Notification::create([
            'user_id'      => $model->id,
            'type'         => 'message_received',
            'title'        => '新しいメッセージが届きました',
            'body'         => "「{$ongoingJob->title}」について {$painter->name} さんからメッセージが届きました。",
            'related_id'   => $ongoingJob->id,
            'related_type' => Job::class,
            'read_at'      => null,
            'created_at'   => now()->subDays(5),
            'updated_at'   => now()->subDays(5),
        ]);

        // モデル宛: 応募が承認されました（既読）
        Notification::create([
            'user_id'      => $model->id,
            'type'         => 'application_accepted',
            'title'        => '応募が承認されました',
            'body'         => "「{$ongoingJob->title}」への応募が承認されました。",
            'related_id'   => $ongoingJob->id,
            'related_type' => Job::class,
            'read_at'      => now()->subDays(5)->addHours(1),
            'created_at'   => now()->subDays(5),
            'updated_at'   => now()->subDays(5),
        ]);

        // 画家宛: 新着応募の通知（Job 3 のもの）
        $latestApp = JobApplication::whereHas('job', fn ($q) => $q->where('painter_id', $painter->id))
            ->where('status', 'pending')
            ->latest()
            ->first();
        if ($latestApp) {
            Notification::create([
                'user_id'      => $painter->id,
                'type'         => 'application_received',
                'title'        => '新しい応募が届きました',
                'body'         => "「{$latestApp->job->title}」に応募がありました。",
                'related_id'   => $latestApp->id,
                'related_type' => JobApplication::class,
                'read_at'      => null,
                'created_at'   => now()->subDays(2),
                'updated_at'   => now()->subDays(2),
            ]);
        }
    }
}
