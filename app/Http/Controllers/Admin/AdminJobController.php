<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class AdminJobController extends Controller
{
    // 求人一覧
    public function index()
    {
        $jobs = Job::with('company')->orderByDesc('created_at')->paginate(20);
        return view('admin.jobs.index', compact('jobs'));
    }

    // 求人詳細
    public function show(Job $job)
    {
        $job->load('company');
        return view('admin.jobs.show', compact('job'));
    }

    // 公開/非公開切り替え
    public function toggleActive(Job $job)
    {
        $job->is_active = !$job->is_active;
        $job->save();
        return back()->with('success', '公開状態を変更しました。');
    }

    // 注目求人フラグ切り替え
    public function toggleFeatured(Job $job)
    {
        $job->is_featured = !$job->is_featured;
        $job->save();
        return back()->with('success', '注目フラグを変更しました。');
    }

    // 募集終了
    public function close(Job $job)
    {
        $job->is_closed = 1;
        $job->is_active = 0;
        $job->application_deadline = now();
        $job->save();
        return back()->with('success', '募集を終了しました。');
    }
}