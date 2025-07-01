<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    /**
     * 資格一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 資格一覧ページのビュー
     */
    public function index()
    {
        // 認証されたユーザーの資格を最新順に取得
        $licenses = auth()->user()->licenses()->latest()->paginate(10);
        return view('licenses.index', compact('licenses'));
    }

    /**
     * 資格の新規作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 資格作成ページのビュー
     */
    public function create()
    {
        return view('licenses.create');
    }

    /**
     * 資格を保存
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function store(Request $request)
    {

        // リクエストのバリデーション
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'acquired_date' => 'required|date',
        ]);
        $data = $validator->validate();
        $data['user_id'] = Auth::id();
        $data['name'] = $request->input('name', null);
        $data['acquired_date'] = $request->input('acquired_date', null);

        // レコード作成
        License::create($data);

        return redirect()->route('mypage')->with('success', '資格を追加しました。');
    }

    /**
     * 資格編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param int $id 資格ID
     * @return \Illuminate\View\View 資格編集ページのビュー
     */
    public function edit(int $id)
    {
        // 資格を取得
        $license = auth()->user()->licenses()->findOrFail($id);
        return view('licenses.edit', compact('license'));
    }

    /**
     * 資格を更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param License $license 資格モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, License $license)
    {

        // バリデーションルールを定義
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'acquired_date' => 'required|date',
        ]);
        $data = $validator->validate();

        // レコード更新
        $license->update($data);

        return redirect()->route('mypage')->with('success', '資格を更新しました。');
    }

    /**
     * 資格を削除
     *
     * @since 1.0.0
     *
     * @param int $id 資格ID
     * @return \Illuminate\Http\RedirectResponse 削除後のリダイレクトレスポンス
     */
    public function destroy($id)
    {
        // 資格を取得
        $license = auth()->user()->licenses()->findOrFail($id);

        // 資格を削除
        $license->delete();

        return redirect()->route('mypage')->with('success', '資格を削除しました。');
    }
}
