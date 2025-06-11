<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class CompanyImageController extends Controller
{
    // 画像一覧（管理画面）
    public function index()
    {
        $company = Auth::guard('company')->user()->company;
        $images = $company->images()->orderBy('order')->get();
        $existingImages = $images->map(function ($img) {
            return [
                'id' => $img->id,
                'url' => Storage::disk('public')->url($img->file_path)
            ];
        });
        return view('company.profiles.images', compact('company', 'existingImages'));
    }

    // 保存（追加・削除・並び替えまとめて）
    public function store(Request $request)
    {
        $company = Auth::guard('company')->user()->company;

        // バリデーション
        $request->validate([
            'existing_images' => 'nullable|array|max:3',
            'existing_images.*' => 'integer|exists:company_images,id',
            'deleted_images' => 'nullable|string',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|max:5120',
        ]);

        $deleted = json_decode($request->input('deleted_images', '[]'), true) ?: [];
        foreach ($deleted as $imgId) {
            $img = $company->images()->find($imgId);
            if ($img) {
                Storage::disk('public')->delete($img->file_path);
                $img->delete();
            }
        }

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

        $order = count($existingIds);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($order >= 3)
                    break;
                $filename = uniqid('company_', true) . '.webp';
                $path = "companies/{$company->id}/images/{$filename}";
                $manager = new ImageManager(new GdDriver());

                $canvas = $manager->create(1200, 675);
                $canvas->fill('ffffff');
                $image = $manager->read($file)->scaleDown(1200, 675);
                $canvas->place($image, 'center');

                $img = $canvas->toWebp(80);
                Storage::disk('public')->put($path, $img->toString());

                $company->images()->create([
                    'file_path' => $path,
                    'order' => $order++,
                ]);
            }
        }

        return back()->with('success', '画像を保存しました');
    }
}
