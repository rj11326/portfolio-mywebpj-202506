<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * 画像を保存し、保存パスを返す
     * 
     * @since 1.0.0
     *
     * @param UploadedFile $file
     * @param string $directory 保存先
     * @param int $width
     * @param int $height
     * @param int $quality 画質（0～100）数字が大きいほど高画質になりファイルサイズ大
     * @return string 保存パス
     */
    public function saveImage(UploadedFile $file, string $directory, int $width = 1200, int $height = 675, int $quality = 80): string
    {
        // ディレクトリが存在しない場合は作成
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        // ファイル名をユニークに生成
        // 例: 5f4dcc3b5aa765d61d832ab7b9c3b8f-webp
        $filename = uniqid('', true) . '.webp';

        // 保存パスを生成
        $path = "{$directory}/{$filename}";

        // 画像マネージャーを使用して画像を処理
        // GDドライバーを使用して画像を読み込み、リサイズ、中央配置
        $manager = new ImageManager(new GdDriver());
        $canvas = $manager->create($width, $height);
        $canvas->fill('ffffff');
        $image = $manager->read($file)->scaleDown($width, $height);
        $canvas->place($image, 'center');
        $img = $canvas->toWebp($quality);

        // 画像を保存
        Storage::disk('public')->put($path, $img->toString());

        return $path;
    }

    /**
     * 画像ファイルを削除
     * 
     * @since 1.0.0
     *
     * @param string $filePath
     * @return void
     */
    public function deleteImage(string $filePath): void
    {
        // 指定されたパスのファイルを削除
        if (!Storage::disk('public')->exists($filePath)) {
            return; // ファイルが存在しない場合は何もしない
        }
        Storage::disk('public')->delete($filePath);
    }
}
