<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyApplication;
use Illuminate\Http\Request;

class CompanyApplyController extends Controller
{
    /**
     * 企業申請フォームの表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 企業申請フォームのビュー
     */
    public function create()
    {
        return view('company.applies.create');
    }

    /**
     * 企業申請フォーム送信処理
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 申請完了後のリダイレクトレスポンス
     */
    public function store(Request $request)
    {
        // リクエストのバリデーション
        $validated = $request->validate([
            'company_name'      => 'required|string|max:255',
            'company_email'     => 'required|email|max:255',
            'company_description' => 'nullable|string|max:2000',
            'contact_name'      => 'required|string|max:255',
            'contact_email'     => 'required|email|max:255',
            'contact_phone'     => 'nullable|string|max:50',
        ]);

        // 企業申請データを保存
        CompanyApplication::create($validated);

        //TODO: メール通知などの処理を追加

        return redirect()->route('company.apply.thanks')->with('status', '申請を受け付けました。運営の審査をお待ちください。');
    }

    /**
     * サンクスページ表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View サンクスページのビュー
     */
    public function thanks()
    {
        return view('company.applies.thanks');
    }
}
