<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * 管理者ログインフォームの表示
     * 
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 管理者ログインページのビュー
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * ログイン処理
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

        // 管理者で認証
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            // 認証成功時はセッションを再生成
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withInput()->with('error', 'メールアドレスまたはパスワードが正しくありません。');
    }

    /**
     * ログアウト処理
     *
     * @since 1.0.0
     * 
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse ログアウト後のリダイレクトレスポンス
     */
    public function logout(Request $request)
    {
        // 管理者ログアウト処理
        Auth::guard('admin')->logout();
        // セッションを無効化し、トークンを再生成
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
