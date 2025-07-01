<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    /**
     * 求人一覧を表示
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View 求人一覧ページのビュー
     */
    public function index(Request $request)
    {
        // 求人一覧を取得
        $query = Job::query()->with(['company', 'location']);

        // キーワード
        if ($request->filled('keyword')) {
            $kw = $request->keyword;
            $query->where(function ($q) use ($kw) {
                $q->where('title', 'like', '%' . $kw . '%')
                    ->orWhere('description', 'like', '%' . $kw . '%');
            });
        }
        // 職種
        if ($request->filled('job_categories')) {
            $ids = array_filter(explode(',', $request->job_categories));
            if (count($ids) > 0) {
                $query->whereIn('job_category_id', $ids);
            }
        }
        // 勤務地
        if ($request->filled('locations')) {
            $ids = array_filter(explode(',', $request->locations));
            if (count($ids) > 0) {
                $query->whereIn('location_id', $ids);
            }
        }
        // 雇用形態
        if ($request->filled('salary')) {
            $query->where('salary_min', '>=', (int) $request->salary * 10000);
        }

        $jobs = $query->latest()->paginate(10)->appends($request->all());
        return view('jobs.index', compact('jobs'));
    }

    /**
     * 求人詳細を表示
     *
     * @since 1.0.0
     *
     * @param int $id 求人ID
     * @return \Illuminate\View\View 求人詳細ページのビュー
     */
    public function show($id)
    {
        // 求人データを取得
        $job = Job::with(['company','company.images' => function ($q) {$q->orderBy('order');}])->findOrFail($id);


        // 企業情報を取得
        // 企業の画像はオプションで取得し、存在しない場合は空のコレクションを使用
        // 画像のURLはストレージから取得し、パスの区切り文字を修正
        // 企業の求人一覧も取得
        $companyImages = optional($job->company)->images ?? collect();
        $imageUrls = $job->company ? $job->company->images()->orderBy('order')->get()->map(fn($i) => str_replace('\\', '/', Storage::disk('public')->url($i->file_path)))->values() : collect();
        $companyJobs = Job::where('company_id', $job->company_id)->where('id', '!=', $job->id)->where('is_active', true)->latest()->take(5)->get();

        return view('jobs.show', compact('job', 'companyJobs', 'companyImages', 'imageUrls'));
    }
}
