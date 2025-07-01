<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;

class JobCategoryApiController extends Controller
{

    /**
     * 求人カテゴリ一覧を取得
     *
     * @since 1.0.0
     *
     * @return \Illuminate\Http\JsonResponse カテゴリ一覧のJSONレスポンス
     */
    public function index()
    {
        // 親カテゴリのみを取得し、子カテゴリを含める
        // 親カテゴリは parent_id が null のもの
        // 子カテゴリは parent_id が親カテゴリの ID と一致するもの
        // with('children') で子カテゴリ情報も一緒に取得
        $categories = JobCategory::whereNull('parent_id')
            ->with('children:id,name,parent_id,slug')
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json(['categories' => $categories]);
    }
}