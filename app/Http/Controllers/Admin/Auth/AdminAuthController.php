<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // ログインフォーム表示
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    // ログイン処理
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withInput()->with('error', 'メールアドレスまたはパスワードが正しくありません。');
    }

    // ログアウト
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
