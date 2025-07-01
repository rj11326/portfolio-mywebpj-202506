<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class AdminJobCategoryController extends Controller
{

    /**
     * 管理者用の求人カテゴリ一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 管理者用求人カテゴリ一覧ページのビュー
     */
    public function index()
    {
        // カテゴリを親子関係で取得し、ID順にソート
        // 親カテゴリはparent_idがnullのもの
        // 子カテゴリはparent_idが親カテゴリのIDと一致するもの
        // with('parent')で親カテゴリ情報も一緒に取得
        $categories = JobCategory::with('parent')->orderBy('id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * 新規カテゴリ作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 新規カテゴリ作成ページのビュー
     */
    public function create()
    {
        // 親カテゴリを取得
        // parent_idがnullのものを取得
        // これにより、トップレベルのカテゴリのみが表示される
        $parents = JobCategory::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    /**
     * 新規カテゴリを保存
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
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_categories,slug',
            'parent_id' => 'nullable|exists:job_categories,id',
            'icon' => 'nullable|string|max:255',
        ]);
        
        // レコード作成
        JobCategory::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを追加しました');
    }

    /**
     * カテゴリ編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param JobCategory $category 編集対象のカテゴリモデル
     * @return \Illuminate\View\View カテゴリ編集ページのビュー
     */
    public function edit(JobCategory $category)
    {
        // 親カテゴリを取得
        // parent_idがnullのものを取得
        $parents = JobCategory::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    /**
     * カテゴリを更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param JobCategory $category 更新対象のカテゴリモデル
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, JobCategory $category)
    {
        // バリデーションルールを定義
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:job_categories,id',
            'icon' => 'nullable|string|max:255',
        ]);

        // レコード更新
        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを更新しました');
    }

    public function destroy(JobCategory $category)
    {
        // カテゴリが親カテゴリの場合は削除不可
        if ($category->children()->exists()) {
            return redirect()->route('admin.categories.index')->with('error', '親カテゴリは削除できません。子カテゴリを先に削除してください。');
        }
        // カテゴリを削除
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを削除しました');
    }
}