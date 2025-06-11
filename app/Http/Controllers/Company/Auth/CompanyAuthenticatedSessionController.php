<?php

namespace App\Http\Controllers\Company\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyAuthenticatedSessionController extends Controller
{
    // ログインフォーム表示
    public function create()
    {
        return view('company.auth.login');
    }

    // ログイン処理
    public function login(Request $request)
    {
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

    // ログアウト処理
    public function logout(Request $request)
    {
        Auth::guard('company')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('company.login');
    }
}
