<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyApplication;
use App\Models\CompanyUser;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\CompanyApproved;
use Illuminate\Support\Facades\Mail;

class AdminCompanyApplicationController extends Controller
{
    /**
     * 申請一覧
     * 
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 企業申請一覧ページのビュー
     */
    public function index()
    {
        // 企業申請を最新順に取得
        // 1ページあたり20件表示
        $applications = CompanyApplication::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.company_applications.index', compact('applications'));
    }

    /**
     * 申請詳細
     * 
     * @since 1.0.0
     *
     * @param int $id 企業申請ID
     * @return \Illuminate\View\View 企業申請詳細ページのビュー
     */
    public function show($id)
    {
        // 企業申請データを取得
        // 存在しない場合は404エラー
        $application = CompanyApplication::findOrFail($id);
        return view('admin.company_applications.show', compact('application'));
    }

    /**
     * 企業申請を承認し、企業アカウントを作成
     *
     * @since 1.0.0
     *
     * @param int $id 企業申請ID
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 承認後のリダイレクトレスポンス
     */
    public function approve($id, Request $request)
    {
        // 申請データを取得
        $application = CompanyApplication::findOrFail($id);

        // 既に承認済みなら何もしない
        if ($application->status === 'approved') {
            return redirect()->back()->with('info', 'すでに承認済みです');
        }

        // companiesテーブルに登録
        $company = Company::create([
            'name' => $application->company_name,
            'email' => $application->company_email,
            'description' => $application->company_description,
        ]);

        // 初期パスワードを自動生成
        $initialPassword = str()->random(12);

        // company_usersテーブルに企業管理者を作成
        $companyUser = CompanyUser::create([
            'company_id' => $company->id,
            'name' => $application->contact_name,
            'email' => $application->contact_email,
            'password' => Hash::make($initialPassword),
            'role' => 1,
        ]);

        // 企業申請情報を更新
        $application->status = 'approved';
        $application->admin_user_id = auth()->id();
        $application->approved_at = now();
        $application->save();

        // メール通知
        Mail::to($companyUser->email)->send(new CompanyApproved($companyUser, $initialPassword));

        return redirect()
            ->route('admin.company_applications.index')
            ->with('success', '企業申請を承認し、企業アカウントを作成しました。初期パスワード: ' . $initialPassword);
    }

    /**
     * 企業申請を却下
     *
     * @since 1.0.0
     *
     * @param int $id 企業申請ID
     * @return \Illuminate\Http\RedirectResponse 却下後のリダイレクトレスポンス
     */
    public function reject($id)
    {
        // 申請データを取得
        $application = CompanyApplication::findOrFail($id);

        // 企業申請情報を更新
        $application->status = 'rejected';
        $application->rejection_reason = request('rejection_reason');
        $application->rejected_at = now();
        $application->admin_user_id = auth()->id();
        $application->save();
        return redirect()->route('admin.company_applications.index')
            ->with('success', '却下しました');
    }
}
