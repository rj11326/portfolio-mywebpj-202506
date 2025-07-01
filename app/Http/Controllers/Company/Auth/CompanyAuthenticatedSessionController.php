<?php

namespace App\Http\Controllers\Company\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyAuthenticatedSessionController extends Controller
{
    /**
     * 企業ログインフォームの表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 企業ログインページのビュー
     */
    public function create()
    {
        return view('company.auth.login');
    }

    /**
     * 企業ログイン処理
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse ログイン成功時のリダイレクトレスポンス
     */
    public function login(Request $request)
    {
        // リクエストのバリデーション
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // companyで認証
        if (Auth::guard('company')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // ログイン後は企業ダッシュボードへ
            return redirect()->intended(route('company.dashboard'));
        }

        // 認証失敗
        return back()->withInput()->with('error', 'メールアドレスまたはパスワードが正しくありません。');
    }

    /**
     * 企業ログアウト処理
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse ログアウト後のリダイレクトレスポンス
     */
    public function logout(Request $request)
    {
        // 企業ログアウト処理
        Auth::guard('company')->logout();

        // セッションを無効化し、トークンを再生成
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('company.login');
    }
}
