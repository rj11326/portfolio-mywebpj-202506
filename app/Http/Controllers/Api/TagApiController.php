<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagApiController extends Controller
{

    /**
     * タグ一覧を取得
     *
     * @since 1.0.0
     *
     * @return \Illuminate\Http\JsonResponse タグ一覧のJSONレスポンス
     */
    public function index()
    {
        // タグをソート順で取得し、必要なカラムのみを選択
        return response()->json([
            'tags' => Tag::select('id', 'label', 'slug')->orderBy('sort_order', 'asc')->get()
        ]);
    }
}