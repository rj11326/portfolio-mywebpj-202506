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
    use AuthorizesRequests;
    // 一覧
    public function index(Request $request)
    {
        $company = $request->user('company')->company;
        $users = CompanyUser::where('company_id', $company->id)->get();
        return view('company.users.index', compact('users'));
    }

    // 新規作成フォーム
    public function create()
    {
        $this->authorize('create', CompanyUser::class);
        return view('company.users.create');
    }

    // 登録処理
    public function store(Request $request)
    {
        $this->authorize('admin');
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

    // 編集フォーム
    public function edit($id)
    {
        $user = CompanyUser::findOrFail($id);
        $this->authorize('edit', $user);
        return view('company.users.edit', compact('user'));
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        $user = CompanyUser::findOrFail($id);
        $this->authorize('update', $user);
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
        $user->update($validated);
        return redirect()->route('company.users.index')->with('success', '担当者情報を更新しました');
    }

    // パスワードリセット
    public function resetPassword($id)
    {
        $user = CompanyUser::findOrFail($id);
        $this->authorize('passwordReset', $user);
        $password = Str::random(10);
        $user->password = Hash::make($password);
        $user->save();

        // パスワードリセットメール送信
        Mail::to($user->email)->send(new PasswordResetMail($user->name, $password));

        return redirect()->route('company.users.index')->with('success', "新しい仮パスワード: {$password}");
    }

    // 削除
    public function destroy($id)
    {
        $currentUser = auth('company')->user();
        $user = CompanyUser::findOrFail($id);
        $this->authorize('destroy', $user);
        if ($currentUser->id === $user->id) {
            return back()->with('error', '自分自身は削除できません。');
        }
        $user->delete();
        return redirect()->route('company.users.index')->with('success', '担当者を削除しました');
    }
}
