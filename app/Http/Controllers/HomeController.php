<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobCategory;

class HomeController extends Controller
{

    /**
     * ホームページの表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View ホームページのビュー
     */
    public function index()
    {
        // 親カテゴリのみを取得
        // 親カテゴリは parent_id が null のもの
        $mainCategories = JobCategory::whereNull('parent_id')->get();

        // 特集求人をランダムに3件取得
        // is_featured が true の求人を対象
        // inRandomOrder() でランダムに並び替え
        $featuredJobs = Job::where('is_featured', true)->inRandomOrder()->take(3)->get();
        return view('home', [
            'mainCategories' => $mainCategories,
            'featuredJobs' => $featuredJobs,
        ]);
    }
}
