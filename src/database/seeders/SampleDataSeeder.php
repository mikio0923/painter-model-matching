<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ModelProfile;
use App\Models\PainterProfile;
use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県',
            '静岡県', '愛知県', '三重県', '滋賀県', '京都府', '大阪府', '兵庫県',
            '奈良県', '和歌山県', '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県', '福岡県', '佐賀県', '長崎県',
            '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        ];

        $genders = ['male', 'female', 'other'];
        $bodyTypes = ['スリム', '普通', 'グラマー', '細身', 'がっしり'];
        $styleTags = [
            '清楚', 'クール', 'セクシー', '可愛い', '大人可愛い', 'ナチュラル',
            'エレガント', 'カジュアル', 'フェミニン', 'モード', 'ストリート',
            'ボヘミアン', 'ガーリー', 'クールガール', 'お姫様', 'ボーイッシュ'
        ];
        $poseRanges = ['全身', 'バストアップ', '顔', '手', '足', '上半身'];

        // モデル20個作成
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => "モデル{$i}",
                'email' => "model{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'model',
                'email_verified_at' => now(),
            ]);

            $age = rand(18, 45);
            $gender = $genders[array_rand($genders)];
            $prefecture = $prefectures[array_rand($prefectures)];
            $height = rand(150, 180);
            $bodyType = $bodyTypes[array_rand($bodyTypes)];
            
            // ランダムなタグを2-5個選択
            shuffle($styleTags);
            $selectedTags = array_slice($styleTags, 0, rand(2, 5));
            shuffle($poseRanges);
            $selectedPoses = array_slice($poseRanges, 0, rand(2, 4));

            // 画像パスを設定（最初の数件に画像を設定）
            $profileImagePath = null;
            if ($i <= 12) {
                // プレースホルダー画像を使用（実際の画像に置き換え可能）
                $imageNumber = (($i - 1) % 12) + 1;
                $profileImagePath = "model_images/sample_model_{$imageNumber}.jpg";
            }

            ModelProfile::create([
                'user_id' => $user->id,
                'display_name' => "モデル{$i}",
                'profile_image_path' => $profileImagePath,
                'age' => $age,
                'gender' => $gender,
                'prefecture' => $prefecture,
                'height' => $height,
                'body_type' => $bodyType,
                'style_tags' => $selectedTags,
                'pose_ranges' => $selectedPoses,
                'online_available' => rand(0, 1) === 1,
                'reward_min' => rand(3000, 8000) * 1000,
                'reward_max' => rand(10000, 30000) * 1000,
                'is_public' => true,
            ]);
        }

        // 画家20個作成
        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => "画家{$i}",
                'email' => "painter{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'painter',
                'email_verified_at' => now(),
            ]);

            $artStyles = ['油彩', '水彩', 'デジタル', 'アクリル', 'ペン画', 'パステル', '色鉛筆', '日本画'];
            $selectedArtStyles = array_slice($artStyles, 0, rand(1, 3));
            shuffle($selectedArtStyles);
            $prefecture = $prefectures[array_rand($prefectures)];

            PainterProfile::create([
                'user_id' => $user->id,
                'display_name' => "画家{$i}",
                'art_styles' => $selectedArtStyles,
                'prefecture' => $prefecture,
            ]);

            // 各画家に1-3個の依頼を作成
            $jobCount = rand(1, 3);
            for ($j = 1; $j <= $jobCount; $j++) {
                $jobTitles = [
                    'モデル募集：人物画制作',
                    'モデル募集：ポートレート撮影',
                    'モデル募集：作品制作',
                    'モデル募集：個展用作品',
                    'モデル募集：練習撮影',
                    'モデル募集：作品制作依頼',
                ];
                $usagePurposes = ['個展', '練習', '作品制作', 'ポートフォリオ', '展示会', null];
                $locationTypes = ['online', 'offline'];
                
                $title = $jobTitles[array_rand($jobTitles)];
                $usagePurpose = $usagePurposes[array_rand($usagePurposes)];
                $locationType = $locationTypes[array_rand($locationTypes)];
                $rewardAmount = rand(5000, 30000) * 1000;
                $rewardUnit = rand(0, 1) === 0 ? 'per_session' : 'per_hour';
                
                Job::create([
                    'painter_id' => $user->id,
                    'title' => $title,
                    'description' => "モデル募集の詳細説明です。{$title}のためのモデルを探しています。ご興味のある方はお気軽にご応募ください。",
                    'usage_purpose' => $usagePurpose,
                    'reward_amount' => $rewardAmount,
                    'reward_unit' => $rewardUnit,
                    'location_type' => $locationType,
                    'prefecture' => $locationType === 'offline' ? $prefecture : null,
                    'city' => $locationType === 'offline' && rand(0, 1) === 1 ? '市区町村' : null,
                    'scheduled_date' => rand(0, 1) === 1 ? now()->addDays(rand(7, 60)) : null,
                    'apply_deadline' => rand(0, 1) === 1 ? now()->addDays(rand(3, 30)) : null,
                    'status' => 'open',
                ]);
            }
        }
    }
}

