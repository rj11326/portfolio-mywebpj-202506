<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    // マイページの表示
    public function mypage()
    {
        $user = Auth::user()->load(['workHistories', 'educations', 'licenses']);
        return view('users.mypage', compact('user'));
    }

    // 個人情報編集フォームの表示
    public function editProfile ()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // 個人情報の更新処理
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_text' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('mypage')->with('success', '情報を更新しました');
    }

    // パスワード変更フォームの表示
    public function editPassword()
    {
        return view('profile.edit_password');
    }

    // パスワード変更処理
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!password_verify($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが正しくありません']);
        }

        $user->update(['password' => bcrypt($request->input('new_password'))]);
        return redirect()->route('mypage')->with('success', 'パスワードを変更しました');
    }
}
