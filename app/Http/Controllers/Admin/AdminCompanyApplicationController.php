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
    // 申請一覧
    public function index()
    {
        $applications = CompanyApplication::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.company_applications.index', compact('applications'));
    }

    // 詳細表示
    public function show($id)
    {
        $application = CompanyApplication::findOrFail($id);
        return view('admin.company_applications.show', compact('application'));
    }

    // 承認
    public function approve($id, Request $request)
    {
        // 申請データ取得
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

    // 却下
    public function reject($id)
    {
        $application = CompanyApplication::findOrFail($id);
        $application->status = 'rejected';
        $application->rejection_reason = request('rejection_reason');
        $application->rejected_at = now();
        $application->admin_user_id = auth()->id();
        $application->save();
        return redirect()->route('admin.company_applications.index')
            ->with('success', '却下しました');
    }
}
