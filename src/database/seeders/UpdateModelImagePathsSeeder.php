<?php

namespace Database\Seeders;

use App\Models\ModelProfile;
use Illuminate\Database\Seeder;

class UpdateModelImagePathsSeeder extends Seeder
{
    /**
     * 既存のモデルプロフィールに画像パスを設定する
     */
    public function run(): void
    {
        $models = ModelProfile::whereNull('profile_image_path')
            ->orWhere('profile_image_path', '')
            ->get();

        foreach ($models as $index => $model) {
            // モデルIDに基づいて画像番号を決定（1-12の範囲で循環）
            $imageNumber = (($model->id - 1) % 12) + 1;
            $imagePath = "model_images/sample_model_{$imageNumber}.jpg";
            
            // 画像ファイルが存在するか確認
            $fullPath = storage_path("app/public/{$imagePath}");
            if (file_exists($fullPath)) {
                $model->update(['profile_image_path' => $imagePath]);
                $this->command->info("Updated model {$model->id} with image path: {$imagePath}");
            } else {
                $this->command->warn("Image file not found: {$fullPath}");
            }
        }

        $this->command->info("Updated {$models->count()} model profiles with image paths.");
    }
}

