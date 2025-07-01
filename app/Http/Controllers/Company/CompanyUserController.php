<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class CompanyUserController extends Controller
{
    // 認可処理トレイト
    use AuthorizesRequests;

    /**
     * 企業の担当者一覧を表示
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View 担当者一覧ページのビュー
     */
    public function index(Request $request)
    {
        // 認証された企業ユーザーから会社情報を取得
        $company = $request->user('company')->company;

        // 担当者一覧を取得
        $users = CompanyUser::where('company_id', $company->id)->get();
        return view('company.users.index', compact('users'));
    }

    /**
     * 新規担当者作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 担当者作成ページのビュー
     */
    public function create()
    {
        // 認可チェック
        $this->authorize('create', CompanyUser::class);
        return view('company.users.create');
    }

    /**
     * 新規担当者を作成
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 作成後のリダイレクトレスポンス
     */    
    public function store(Request $request)
    {
        // 認可チェック
        $this->authorize('admin');

        // バリデーションルールを定義
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:company_users,email',
            'role' => ['required', Rule::in(['admin', 'member'])],
            'password' => 'nullable|string|min:8',
        ]);

        // 仮パスワード自動発行（未入力時）
        $password = $validated['password'] ?? Str::random(10);
        $validated['password'] = Hash::make($password);
        $validated['company_id'] = auth('company')->user()->company_id;
        $user = CompanyUser::create($validated);

        // TODO 仮パスワードをメール通知
        // Mail::to($user->email)->send(new PasswordResetMail($user->name, $password));

        return redirect()->route('company.users.index')->with('success', "担当者を作成しました。仮パスワード: {$password}");
    }

    /**
     * 担当者の編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param int $id 担当者ID
     * @return \Illuminate\View\View 担当者編集ページのビュー
     */
    public function edit($id)
    {
        // 担当者情報を取得
        $user = CompanyUser::findOrFail($id);
        // 認可チェック
        $this->authorize('edit', $user);
        return view('company.users.edit', compact('user'));
    }

    /**
     * 担当者情報を更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $id 担当者ID
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, $id)
    {
        // 担当者情報を取得
        $user = CompanyUser::findOrFail($id);

        // 認可チェック
        $this->authorize('update', $user);

        // バリデーションルールを定義
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('company_users', 'email')->ignore($user->id)
            ],
            'role' => ['required', Rule::in(['admin', 'member'])],
        ]);
        
        // レコードの更新
        $user->update($validated);
        return redirect()->route('company.users.index')->with('success', '担当者情報を更新しました');
    }

    /**
     * 担当者のパスワードをリセット
     *
     * @since 1.0.0
     *
     * @param int $id 担当者ID
     * @return \Illuminate\Http\RedirectResponse パスワードリセット後のリダイレクトレスポンス
     */
    public function resetPassword($id)
    {
        // 企業ユーザーから担当者情報を取得
        $user = CompanyUser::findOrFail($id);

        // 認可チェック
        $this->authorize('passwordReset', $user);

        // パスワードをランダムに生成
        // ここでは10文字のランダムな文字列を生成
        $password = Str::random(10);

        // パスワードをハッシュ化して保存
        $user->password = Hash::make($password);

        // 担当者の情報を更新
        $user->save();

        // パスワードリセットメール送信
        Mail::to($user->email)->send(new PasswordResetMail($user->name, $password));

        return redirect()->route('company.users.index')->with('success', "新しい仮パスワード: {$password}");
    }

    /**
     * 担当者を削除
     *
     * @since 1.0.0
     *
     * @param int $id 担当者ID
     * @return \Illuminate\Http\RedirectResponse 削除後のリダイレクトレスポンス
     */
    public function destroy($id)
    {
        // 認証された企業ユーザーを取得
        $currentUser = auth('company')->user();

        // 担当者情報を取得
        $user = CompanyUser::findOrFail($id);

        // 認可チェック
        $this->authorize('destroy', $user);

        // 自分自身の削除は許可しない
        if ($currentUser->id === $user->id) {
            return back()->with('error', '自分自身は削除できません。');
        }

        // 担当者を削除
        $user->delete();
        return redirect()->route('company.users.index')->with('success', '担当者を削除しました');
    }
}
