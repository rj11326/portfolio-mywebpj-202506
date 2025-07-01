<?php

namespace App\Http\Controllers\Company;

use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Models\CompanyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class CompanyImageController extends Controller
{
    /**
     * 企業の画像一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 企業画像管理ページのビュー
     */
    public function index()
    {
        // 認証された企業ユーザーの情報を取得
        $company = Auth::guard('company')->user()->company;
        // 企業の画像を取得し、順番に並べ替え
        $images = $company->images()->orderBy('order')->get();
        // 画像のURLを生成して配列に変換
        $existingImages = $images->map(function ($img) {
            return [
                'id' => $img->id,
                'url' => Storage::disk('public')->url($img->file_path)
            ];
        });
        return view('company.profiles.images', compact('company', 'existingImages'));
    }

    /**
     * 保存（追加・削除・並び替えまとめて）
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function store(Request $request, ImageService $imageService)
    {
        // 認証された企業ユーザーの情報を取得
        $company = Auth::guard('company')->user()->company;

        // バリデーション
        $request->validate([
            'existing_images' => 'nullable|array|max:3',
            'existing_images.*' => 'integer|exists:company_images,id',
            'deleted_images' => 'nullable|string',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|max:5120',
        ]);

        // 既存の画像を削除
        // deleted_imagesはJSON形式で送信されることを想定
        $deleted = json_decode($request->input('deleted_images', '[]'), true) ?: [];
        foreach ($deleted as $imgId) {
            $img = $company->images()->find($imgId);
            if ($img) {
                $imageService->deleteImage($img->file_path);
                $img->delete();
            }
        }

        // 既存の画像の並び替え
        // existing_imagesは配列で送信されることを想定
        // 画像IDの配列を取得し、順番に並べ替え
        // 画像のorderフィールドを更新する
        $existingIds = $request->input('existing_images', []);
        if (is_array($existingIds)) {
            // 一時的にユニークな値（-imgId）を入れる
            foreach ($existingIds as $imgId) {
                $img = $company->images()->find($imgId);
                if ($img) {
                    $img->order = -$img->id;
                    $img->save();
                }
            }
            // 正しい順に再セット
            foreach ($existingIds as $idx => $imgId) {
                $img = $company->images()->find($imgId);
                if ($img) {
                    $img->order = $idx;
                    $img->save();
                }
            }
        }

        // 新しい画像を追加
        // 既存の画像数をカウント
        // 最大3枚まで追加可能
        // 画像は1200x675にリサイズし、中央に配置してWebP形式で保存
        $order = count($existingIds);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($order >= 3)
                    break;
                $path = $imageService->saveImage(
                    $file,
                    "companies/{$company->id}/images",
                    1200,
                    675,
                    80
                );
                $company->images()->create([
                    'file_path' => $path,
                    'order' => $order++,
                ]);
            }
        }

        return back()->with('success', '画像を保存しました');
    }
}
