<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!password_verify($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが正しくありません']);
        }

        $user->update(['password' => bcrypt($request->input('password'))]);
        return redirect()->route('mypage')->with('success', 'パスワードを変更しました');
    }
}