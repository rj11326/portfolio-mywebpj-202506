<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    use AuthorizesRequests;

    /**
     * 学歴一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 学歴一覧ページのビュー
     */
    public function index()
    {
        // 学歴を取得
        $educations = auth()->user()->educations()->latest()->paginate(10);
        return view('educations.index', compact('educations'));
    }

    /**
     * 学歴の新規作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 学歴作成ページのビュー
     */
    public function create()
    {
        return view('educations.create');
    }

    /**
     * 学歴を保存
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
            'school_name' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'major' => 'nullable|string|max:255',
        ]);
        $data = $validator->validate();
        $data['user_id'] = Auth::id();
        $data['end_date'] = $request->input('end_date', null);
        $data['degree'] = $request->input('degree', null);
        $data['major'] = $request->input('major', null);

        // レコード作成
        Education::create($data);

        return redirect()->route('mypage')->with('success', '学歴を追加しました。');
    }

    /**
     * 学歴編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param int $id 学歴ID
     * @return \Illuminate\View\View 学歴編集ページのビュー
     */
    public function edit(int $id)
    {
        // 学歴を取得
        $education = auth()->user()->educations()->findOrFail($id);
        return view('educations.edit', compact('education'));
    }

    /**
     * 学歴を更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param Education $education 学歴モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, Education $education)
    {
        // リクエストのバリデーション
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'major' => 'nullable|string|max:255',
        ]);
        $data = $validator->validate();
        $data['end_date'] = $request->input('end_date', null);
        $data['degree'] = $request->input('degree', null);
        $data['major'] = $request->input('major', null);

        // 認可チェック
        $this->authorize('update', $education);

        // 学歴を更新
        $education->update($data);

        return redirect()->route('mypage')->with('success', '学歴を更新しました。');
    }

    /**
     * 学歴を削除
     *
     * @since 1.0.0
     *
     * @param int $id 学歴ID
     * @return \Illuminate\Http\RedirectResponse 削除後のリダイレクトレスポンス
     */
    public function destroy($id)
    {
        // 学歴を取得
        $education = auth()->user()->educations()->findOrFail($id);

        // 学歴を削除
        $education->delete();

        return redirect()->route('mypage')->with('success', '学歴を削除しました。');
    }
}
