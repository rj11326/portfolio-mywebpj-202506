<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class AdminJobController extends Controller
{
    /**
     * 管理者用の求人一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 管理者用求人一覧ページのビュー
     */
    public function index()
    {
        // 求人を最新順に取得
        $jobs = Job::with('company')->orderByDesc('created_at')->paginate(20);
        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * 求人詳細を表示
     *
     * @since 1.0.0
     *
     * @param Job $job 求人モデルインスタンス
     * @return \Illuminate\View\View 求人詳細ページのビュー
     */
    public function show(Job $job)
    {
        // 求人モデルに関連する企業情報をロード
        // これにより、求人詳細ページで企業情報を表示できる
        $job->load('company');
        return view('admin.jobs.show', compact('job'));
    }

    /**
     * 求人の公開状態を切り替え
     *
     * @since 1.0.0
     *
     * @param Job $job 求人モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 切り替え後のリダイレクトレスポンス
     */
    public function toggleActive(Job $job)
    {
        // 求人の公開状態を反転
        // is_activeが1なら0に、0なら1に切り替える
        $job->is_active = !$job->is_active;
        $job->save();
        return back()->with('success', '公開状態を変更しました。');
    }

    /**
     * 求人の注目フラグを切り替え
     *
     * @since 1.0.0
     *
     * @param Job $job 求人モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 切り替え後のリダイレクトレスポンス
     */
    public function toggleFeatured(Job $job)
    {
        // 求人の注目フラグを反転
        // is_featuredが1なら0に、0なら1に切り替える
        $job->is_featured = !$job->is_featured;
        $job->save();
        return back()->with('success', '注目フラグを変更しました。');
    }

    /**
     * 募集を終了
     *
     * @since 1.0.0
     *
     * @param Job $job 求人モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 募集終了後のリダイレクトレスポンス
     */
    public function close(Job $job)
    {
        // 募集を終了するための処理
        // is_closedを1に設定し、is_activeを0にする
        // application_deadlineを現在日時に設定
        $job->is_closed = 1;
        $job->is_active = 0;
        $job->application_deadline = now();
        $job->save();
        return back()->with('success', '募集を終了しました。');
    }
}