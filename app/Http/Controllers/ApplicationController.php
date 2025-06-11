<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Job;
use App\Mail\ApplicationSubmitted;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $applications = Application::with('job.company')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('applications.index', compact('applications'));
    }

    // 応募画面表示
    public function create(Job $job)
    {
        return view('applications.create', compact('job'));
    }

    public function store(Request $request, $jobId)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'motivation' => 'nullable|string|max:2000',
            'resume' => 'nullable|array',
            'resume.*' => 'file|mimes:pdf,doc,docx|max:4096',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // すでに応募していないかチェック
        $already = Application::where('user_id', $user->id)->where('job_id', $jobId)->exists();
        if ($already) {
            return redirect()->back()->with('error', 'すでに応募済みです。');
        }

        // 応募を保存
        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
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

        // 企業メールアドレス取得
        $company = $application->job->company;
        $toEmail = $company->email ?? null;

        // メール送信
        if ($toEmail) {
            Mail::to($toEmail)->send(new ApplicationSubmitted($application));
        }

        return redirect()->route('applications.thanks')
            ->with('success', '応募が完了しました。企業からの連絡をお待ちください。');
    }

}