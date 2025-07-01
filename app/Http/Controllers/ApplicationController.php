<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Job;
use App\Models\Message;
use App\Mail\ApplicationSubmitted;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{

    /**
     * 応募一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 応募一覧ページのビュー
     */
    public function index()
    {
        // 認証されたユーザーを取得
        $user = auth()->user();

        // ユーザーが認証されていない場合はログインページへリダイレクト
        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーの応募情報を取得
        $applications = Application::with('job.company')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('applications.index', compact('applications'));
    }

    /**
     * 応募フォームを表示
     *
     * @since 1.0.0
     *
     * @param Job $job 求人モデルインスタンス
     * @return \Illuminate\View\View 応募フォームページのビュー
     */
    public function create(Job $job)
    {
        return view('applications.create', compact('job'));
    }

    /**
     * 応募を保存
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $jobId 求人ID
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function store(Request $request, $jobId)
    {
        // バリデーションルールを定義
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'motivation' => 'nullable|string|max:2000',
            'resume' => 'nullable|array',
            'resume.*' => 'file|mimes:pdf,doc,docx|max:4096',
        ]);

        // 認証されたユーザーを取得
        $user = auth()->user();

        // ユーザーが認証されていない場合はログインページへリダイレクト
        if (!$user) {
            return redirect()->route('login');
        }

        // すでに応募していないかチェック
        $already = Application::where('user_id', $user->id)->where('job_id', $jobId)->exists();
        if ($already) {
            // すでに応募済みの場合はエラーメッセージを表示
            return redirect()->back()->with('error', 'すでに応募済みです。');
        }

        // 求人情報を取得
        $job = Job::findOrFail($jobId);

        // 応募を保存
        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'company_id' => $job->company_id,
            'message' => $request->message,
            'motivation' => $request->motivation,
        ]);

        // 職務経歴書ファイルがある場合（複数ファイル対応）
        if ($request->hasFile('resume')) {
            $files = $request->file('resume');
            foreach ($files as $file) {
                $path = $file->store('resumes', 'public');
                $application->files()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // 自動返信メッセージを送信
        // 応募に関連する求人情報から自動返信メッセージを取得
        $job = $application->job;
        $autoReply = $job->auto_reply_message;
        if (!empty($autoReply)) {
            Message::create([
                'application_id' => $application->id,
                'sender_type' => 1, // 1: 企業
                'sender_id' => $job->company_id,
                'message' => $autoReply,
                'is_read' => false,
            ]);
        }

        // 企業メールアドレス取得
        $company = $application->job->company;
        $toEmail = $company->email ?? null;

        // メール送信
        if ($toEmail) {
            Mail::to($toEmail)->send(new ApplicationSubmitted($application));
        }

        return redirect()->route('applications.thanks')->with('success', '応募が完了しました。企業からの連絡をお待ちください。');
    }

}