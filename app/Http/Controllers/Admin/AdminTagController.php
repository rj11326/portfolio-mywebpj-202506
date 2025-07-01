<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminTagController extends Controller
{
    /**
     * タグ一覧
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View タグ一覧ページのビュー
     */
    public function index()
    {
        // タグをsort_order順に取得
        // sort_orderが同じ場合はid順でソート
        $tags = Tag::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * 新規タグ作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 新規タグ作成ページのビュー
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * 新規タグを保存
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function store(Request $request)
    {
        // バリデーションルールを定義
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
            'sort_order' => ['nullable', 'integer'],
        ]);

        // sort_order未入力または重複時は自動で最大値+1
        if (empty($validated['sort_order']) || Tag::where('sort_order', $validated['sort_order'])->exists()) {
            $max = Tag::max('sort_order');
            $validated['sort_order'] = is_null($max) ? 1 : $max + 1;
        }

        // レコード作成
        Tag::create($validated);
        return redirect()->route('admin.tags.index')->with('success', 'タグを追加しました');
    }

    /**
     * タグ編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param Tag $tag タグモデルインスタンス
     * @return \Illuminate\View\View タグ編集ページのビュー
     */
    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * タグを更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param Tag $tag タグモデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, Tag $tag)
    {
        // バリデーションルールを定義
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
            'sort_order' => ['nullable', 'integer'],
        ]);

        // sort_order未入力または重複時は自動で最大値+1
        if (empty($validated['sort_order']) || Tag::where('sort_order', $validated['sort_order'])->where('id', '!=', $tag->id)->exists()) {
            $max = Tag::max('sort_order');
            $validated['sort_order'] = is_null($max) ? 1 : $max + 1;
        }

        // レコードの更新
        $tag->update($validated);
        return redirect()->route('admin.tags.index')->with('success', 'タグを更新しました');
    }

    /**
     * タグを削除
     *
     * @since 1.0.0
     *
     * @param Tag $tag タグモデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 削除後のリダイレクトレスポンス
     */
    public function destroy(Tag $tag)
    {
        // タグが使用中の場合は削除できない
        if ($tag->jobs()->exists()) {
            return redirect()->route('admin.tags.index')->with('error', 'このタグは使用中のため削除できません');
        }
        // レコード削除
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'タグを削除しました');
    }
}