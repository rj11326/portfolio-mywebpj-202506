<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedJobController extends Controller
{

    /**
     * 保存された求人一覧を表示
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View 保存された求人一覧のビュー
     */
    public function index(Request $request)
    {
        // 保存された求人を取得
        $jobs = $request->user()->savedJobs()->with('company')->get();
        return view('saved_jobs.index', compact('jobs'));
    }

}
