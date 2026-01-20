<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /**
     * 画像を最適化して保存（GDライブラリを使用）
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @return string 保存されたパス
     */
    public static function optimizeAndStore(
        UploadedFile $file,
        string $directory = 'uploads',
        int $maxWidth = 1200,
        int $maxHeight = 1200,
        int $quality = 85
    ): string {
        if (!extension_loaded('gd')) {
            // GDライブラリが利用できない場合は通常の保存
            return $file->store($directory, 'public');
        }

        $tempPath = $file->getRealPath();
        $imageInfo = getimagesize($tempPath);
        
        if (!$imageInfo) {
            return $file->store($directory, 'public');
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;

        // リサイズが必要かチェック
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            return $file->store($directory, 'public');
        }

        // アスペクト比を維持してリサイズ
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        $newWidth = (int)($originalWidth * $ratio);
        $newHeight = (int)($originalHeight * $ratio);

        // 元画像を読み込み
        $sourceImage = match ($imageType) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($tempPath),
            IMAGETYPE_PNG => imagecreatefrompng($tempPath),
            IMAGETYPE_GIF => imagecreatefromgif($tempPath),
            IMAGETYPE_WEBP => imagecreatefromwebp($tempPath),
            default => null,
        };

        if (!$sourceImage) {
            return $file->store($directory, 'public');
        }

        // 新しい画像を作成
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // PNGとGIFの透過を保持
        if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_GIF) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefill($newImage, 0, 0, $transparent);
        }

        // リサイズ
        imagecopyresampled(
            $newImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight
        );

        // 一時ファイルに保存
        $filename = time() . '_' . uniqid() . '.jpg';
        $path = $directory . '/' . $filename;
        $fullPath = Storage::disk('public')->path($path);

        // ディレクトリを作成
        $directoryPath = dirname($fullPath);
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }

        // JPEGとして保存（圧縮）
        imagejpeg($newImage, $fullPath, $quality);

        // メモリを解放
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $path;
    }
}

