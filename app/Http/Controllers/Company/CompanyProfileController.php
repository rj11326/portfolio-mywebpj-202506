<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyProfileController extends Controller
{

    /**
     * 企業のプロフィールを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 会社情報ページのビュー
     */
    public function show()
    {
        // 認証された企業ユーザーから会社情報を取得
        $company = Auth::guard('company')->user()->company;
        return view('company.profiles.show', compact('company'));
    }

    /**
     * 企業のプロフィール編集フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View プロフィール編集ページのビュー
     */
    public function edit()
    {
        // 認証された企業ユーザーから会社情報を取得
        $company = Auth::guard('company')->user()->company;
        return view('company.profiles.edit', compact('company'));
    }

    /**
     * 企業のプロフィールを更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request)
    {
        // 認証された企業ユーザーから会社情報を取得
        $company = Auth::guard('company')->user()->company;

        // リクエストのバリデーション
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'description'     => 'nullable|string|max:2000',
            'founded_at'      => 'nullable|date',
            'capital'         => 'nullable|integer',
            'employee_count'  => 'nullable|integer',
        ]);

        // 会社情報を更新
        $company->update($validated);

        return redirect()->route('company.profiles.show')->with('status', '会社情報を更新しました');
    }
}
