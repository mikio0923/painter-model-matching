<?php

namespace Database\Seeders;

use App\Models\ModelProfile;
use App\Models\ModelProfileImage;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ModelProfileDiarySeeder extends Seeder
{
    /**
     * ポートフォリオ・日記のサンプルデータをシード
     */
    public function run(): void
    {
        $captions = [
            '今日は写真撮影の仕事でした。新しい衣装で楽しく撮影できました！',
            '先日の撮影会でいただいた写真。雰囲気が気に入っています。',
            'モデル活動5年目。これからもいろいろなジャンルに挑戦していきたいです。',
            '衣装製作が趣味なので、自分で作ったドレスで撮影してもらいました。',
            'フィギュアスケート観戦归来。インスピレーションをもらってます。',
            'ベランダのガーデニング、癒しの時間です。',
            'コスプレ撮影会に参加。衣装が似合ってるか不安だったけど好評でした。',
            'ヴィンテージのドレス、お気に入りの一品です。',
            '絵画モデルのお仕事。長時間のポーズは大変ですが、作品の一部になれるのが嬉しい。',
            '個人撮影の様子。クリエイターの方とお話ししながらの撮影は勉強になります。',
            'ブライダルモデルの経験を活かして。白いドレスはやっぱり特別感がありますね。',
            '今日はロケ撮影。初めての場所で新鮮な気持ちで臨めました。',
            'グラビア撮影の合間のひとコマ。スタッフの皆さんに感謝。',
            '着物姿で撮影。和装も好きなのでまた機会があれば嬉しいです。',
            '民族衣装のコレクション。撮影に使える衣装が増えてきました。',
            'カフェでのスナップ。オフの日も何かしら撮影したくなります。',
            '新しいヘアスタイルで撮影。 change is good!',
            '季節の変わり目。衣替えついでにポートフォリオも更新しました。',
        ];

        $modelProfiles = ModelProfile::all();

        if ($modelProfiles->isEmpty()) {
            $this->command->warn('モデルプロフィールが存在しません。先に SampleDataSeeder を実行してください。');
            return;
        }

        foreach ($modelProfiles as $index => $modelProfile) {
            // 各モデルに2〜5件の日記エントリを作成
            $entryCount = rand(2, 5);

            for ($i = 0; $i < $entryCount; $i++) {
                // 12枚のサンプル画像から重複しないよう選択（足りない場合は循環）
                $imageNumber = (($index * 7 + $i) % 12) + 1;
                $imagePath = "model_images/sample_model_{$imageNumber}.jpg";

                // 過去7〜90日以内のランダムな日付
                $daysAgo = rand(1, 90);
                $createdAt = Carbon::now()->subDays($daysAgo);

                $caption = $captions[array_rand($captions)];

                ModelProfileImage::create([
                    'model_profile_id' => $modelProfile->id,
                    'image_path' => $imagePath,
                    'display_order' => $i + 1,
                    'is_main' => false,
                    'caption' => $caption,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $this->command->info('ポートフォリオ・日記のシードが完了しました。');
    }
}
