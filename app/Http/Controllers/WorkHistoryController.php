<?php

namespace App\Http\Controllers;

use App\Models\WorkHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class WorkHistoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * 職歴一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 職歴一覧ページのビュー
     */
    public function index()
    {
        // 認証されたユーザーの職歴を取得
        $workHistories = Auth::user()->workHistories;
        return view('workhistories.index', compact('workHistories'));
    }

    /**
     * 職歴の新規作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 職歴作成ページのビュー
     */
    public function create()
    {
        return view('workhistories.create');
    }

    /**
     * 職歴を保存
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function store(Request $request)
    {
        // バリデーションルールを定義
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_current' => 'nullable|boolean',
            'start_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // is_current が false のときだけ end_date を検証
        $validator->sometimes('end_date', 'required|date|after_or_equal:start_date', function ($input) {
            return !$input->is_current;
        });

        $data = $validator->validate();

        $data['user_id'] = Auth::id();
        $data['end_date'] = $request->input('end_date', null);
        $data['is_current'] = $request->boolean('is_current');

        // レコード作成
        WorkHistory::create($data);

        return redirect()->route('mypage')->with('success', '職歴を追加しました。');
    }

    /**
     * 職歴編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param int $id 職歴ID
     * @return \Illuminate\View\View 職歴編集ページのビュー
     */
    public function edit(int $id)
    {
        // 職歴の詳細を取得
        $workHistory = WorkHistory::findOrFail($id);
        return view('workhistories.edit', compact('workHistory'));
    }

    /**
     * 職歴を更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param WorkHistory $workhistory 更新対象の職歴モデル
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, WorkHistory $workhistory)
    {
        // バリデーションルールを定義
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_current' => 'nullable|boolean',
            'start_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // is_current が false のときだけ end_date を検証
        $validator->sometimes('end_date', 'required|date|after_or_equal:start_date', function ($input) {
            return !$input->is_current;
        });

        $data = $validator->validate();

        $data['end_date'] = $request->input('end_date', null);
        $data['is_current'] = $request->boolean('is_current');
        $data['tags'] = json_encode($request->input('tags', []));

        $data['tags'] = json_encode($data['tags'] ?? []);

        // 認証されたユーザーが職歴を更新できるか確認
        $this->authorize('update', $workhistory);
        // 職歴を更新
        $workhistory->update($data);

        return redirect()->route('mypage')->with('success', '職歴を更新しました。');
    }

    /**
     * 職歴を削除
     *
     * @since 1.0.0
     *
     * @param int $id 職歴ID
     * @return \Illuminate\Http\RedirectResponse 削除後のリダイレクトレスポンス
     */
    public function destroy(int $id)
    {
        // 職歴の詳細を取得
        $workHistory = WorkHistory::findOrFail($id);

        // 認証されたユーザーが職歴を削除できるか確認
        $this->authorize('delete', $workHistory);
        
        // 職歴を削除
        $workHistory->delete();

        return redirect()->route('mypage')->with('success', '職歴を削除しました。');
    }
}