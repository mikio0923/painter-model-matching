<?php

namespace Database\Seeders;

use App\Models\ModelProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BulkModelSeeder extends Seeder
{
    public function run(): void
    {
        $count = 300;

        $prefectures = [
            '北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
            '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
            '新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県',
            '静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県',
            '奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県',
            '徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県',
            '熊本県','大分県','宮崎県','鹿児島県','沖縄県',
        ];
        $genders   = ['male', 'female', 'other'];
        $bodyTypes = ['スリム', '普通', 'グラマー', '細身', 'がっしり'];
        $hairTypes = ['short', 'medium', 'long', 'semi_long', 'super_long', 'other'];
        $styleTags = [
            '清楚','クール','セクシー','可愛い','大人可愛い','ナチュラル',
            'エレガント','カジュアル','フェミニン','モード','ストリート',
            'ボヘミアン','ガーリー','クールガール','お姫様','ボーイッシュ',
        ];
        $modelTypesPool = ['ヌード','着衣','ファッション','コスプレ','ポートレート'];
        $poseRanges    = ['全身','バストアップ','顔','手','足','上半身'];
        $occupations   = ['学生','フリーランス','会社員','クリエイター','美容師','講師','フリーター'];

        $startIndex = User::where('role', 'model')->count() + 1;

        DB::transaction(function () use (
            $count, $startIndex, $prefectures, $genders, $bodyTypes, $hairTypes,
            $styleTags, $modelTypesPool, $poseRanges, $occupations
        ) {
            for ($i = 0; $i < $count; $i++) {
                $n = $startIndex + $i;

                $user = User::create([
                    'name'              => "モデル{$n}",
                    'email'             => "bulk_model_{$n}@example.com",
                    'password'          => Hash::make('password'),
                    'role'              => 'model',
                    'email_verified_at' => now(),
                ]);

                $age        = rand(18, 45);
                $gender     = $genders[array_rand($genders)];
                $prefecture = $prefectures[array_rand($prefectures)];
                $height     = rand(150, 180);
                $bodyType   = $bodyTypes[array_rand($bodyTypes)];
                $hairType   = $hairTypes[array_rand($hairTypes)];

                shuffle($styleTags);
                $selectedTags = array_slice($styleTags, 0, rand(2, 5));
                shuffle($poseRanges);
                $selectedPoses = array_slice($poseRanges, 0, rand(2, 4));
                shuffle($modelTypesPool);
                $selectedTypes = array_slice($modelTypesPool, 0, rand(1, 3));

                $imageNumber       = (($i) % 12) + 1;
                $profileImagePath  = "model_images/sample_model_{$imageNumber}.jpg";

                $rewardMin = rand(3, 8) * 1000;
                $rewardMax = $rewardMin + rand(2, 20) * 1000;

                ModelProfile::create([
                    'user_id'            => $user->id,
                    'display_name'       => "モデル{$n}",
                    'profile_image_path' => $profileImagePath,
                    'age'                => $age,
                    'gender'             => $gender,
                    'prefecture'         => $prefecture,
                    'height'             => $height,
                    'bust'               => rand(75, 95),
                    'waist'              => rand(55, 75),
                    'hip'                => rand(80, 100),
                    'body_type'          => $bodyType,
                    'hair_type'          => $hairType,
                    'occupation'         => $occupations[array_rand($occupations)],
                    'model_types'        => $selectedTypes,
                    'style_tags'         => $selectedTags,
                    'pose_ranges'        => $selectedPoses,
                    'online_available'   => rand(0, 1) === 1,
                    'reward_min'         => $rewardMin,
                    'reward_max'         => $rewardMax,
                    'identity_verified'  => rand(0, 4) === 0,
                    'is_public'          => true,
                ]);
            }
        });

        $this->command->info("Bulk created {$count} model profiles. (start index: {$startIndex})");
    }
}
