<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

    /**
     * マイページの表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View マイページのビュー
     */
    public function mypage()
    {
        // 認証されたユーザーの情報を取得
        $user = Auth::user()->load(['workHistories', 'educations', 'licenses']);
        return view('users.mypage', compact('user'));
    }


    /**
     * 個人情報編集フォームの表示
     * 
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 個人情報編集ページのビュー
     */
    public function editProfile ()
    {
        // 認証されたユーザーの情報を取得
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * 個人情報を更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function updateProfile(Request $request)
    {
        // バリデーションルールの定義
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

    /**
     * パスワード変更フォームの表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View パスワード変更ページのビュー
     */
    public function editPassword()
    {
        return view('profile.edit_password');
    }

    /**
     * パスワードを更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function updatePassword(Request $request)
    {
        // バリデーションルールの定義
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // 現在のパスワードの確認
        // Auth::user()で現在のユーザーを取得し、password_verifyで確認
        // パスワードはハッシュ化されているため、bcryptでハッシュ化された値と比較
        // もし現在のパスワードが正しくない場合はエラーを返す
        $user = Auth::user();
        if (!password_verify($request->input('current_password'), (string) $user->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが正しくありません']);
        }

        // 新しいパスワードをハッシュ化して保存
        // bcrypt関数を使用してパスワードをハッシュ化
        $user->update(['password' => bcrypt($request->input('new_password'))]);
        return redirect()->route('mypage')->with('success', 'パスワードを変更しました');
    }
}
