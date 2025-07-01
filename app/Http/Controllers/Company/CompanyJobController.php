<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Tag;
use App\Models\JobCategory;
use App\Models\Location;
use App\Models\Area;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyJobController extends Controller
{
    // 認可処理トレイト
    use AuthorizesRequests;

    /**
     * 企業の求人一覧を表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 求人一覧ページのビュー
     */
    public function index(Request $request)
    {
        // 認証された企業ユーザーの情報を取得
        $companyId = Auth::guard('company')->user()->company_id;
        // 求人を最新順に取得
        $jobs = Job::with(['tags', 'jobCategory', 'location'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(20);

        return view('company.jobs.index', compact('jobs'));
    }

    /**
     * 求人の新規作成フォームを表示
     *
     * @since 1.0.0
     *
     * @return \Illuminate\View\View 新規求人作成ページのビュー
     */
    public function create()
    {
        // タグ、カテゴリ、ロケーションの情報を取得
        $allTags = Tag::orderBy('sort_order')->get();
        $categories = JobCategory::with('children')->whereNull('parent_id')->get();
        $areas = Area::with([
            'locations' => function ($q) {
                $q->orderBy('sort_order');
            }
        ])->orderBy('sort_order')->get();

        return view('company.jobs.create', [
            'allTags' => $allTags,
            'categories' => $categories,
            'areas' => $areas,
            'selectedTags' => [],
            'job' => null,
        ]);
    }

    /**
     * 新規求人を保存
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function store(Request $request)
    {
        // バリデーションルールを取得
        $validated = $request->validate($this->getValidationRules());

        // 認証された企業ユーザーの情報を取得
        $validated['company_id'] = Auth::guard('company')->user()->company_id;
        // レコード作成
        $job = Job::create($validated);

        // タグ紐付け
        $job->tags()->sync($request->input('tags', []));

        return redirect()->route('company.jobs.index')->with('success', '求人を作成しました');
    }

    /** 
     * 求人編集フォームを表示
     *
     * @since 1.0.0
     *
     * @param int $id 求人ID
     * @return \Illuminate\View\View 求人編集ページのビュー
     */
    public function edit($id)
    {
        // 求人データを取得
        $job = Job::with('tags')->findOrFail($id);

        // 認可チェック
        $this->authorize('update', $job);

        // タグ、カテゴリ、ロケーションの情報を取得
        $allTags = Tag::orderBy('sort_order')->get();
        $categories = JobCategory::all();
        $locations = Location::all();
        $selectedTags = $job->tags->pluck('id')->toArray();

        return view('company.jobs.edit', [
            'job' => $job,
            'allTags' => $allTags,
            'categories' => $categories,
            'locations' => $locations,
            'selectedTags' => $selectedTags,
        ]);
    }

    /**
     * 求人の更新処理
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $id 求人ID
     * @return \Illuminate\Http\RedirectResponse 更新後のリダイレクトレスポンス
     */
    public function update(Request $request, $id)
    {
        // 求人データを取得
        $job = Job::findOrFail($id);

        // 認可チェック
        $this->authorize('update', $job);

        // バリデーションルールを取得
        $validated = $request->validate($this->getValidationRules());

        // 求人の更新
        $job->update($validated);

        // タグ紐付け
        $job->tags()->sync($request->input('tags', []));

        return redirect()->route('company.jobs.index')->with('success', '求人を更新しました');
    }


    /**
     * 求人のプレビューを表示
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View 求人プレビューページのビュー
     */
    public function preview(Request $request)
    {
        // リクエストから求人データを取得して新規Jobインスタンスを作成
        $jobData = $request->all();
        $job = new Job($jobData);

        // 会社情報をセット（通常はログインユーザーの会社情報を使う）
        $job->company_id = Auth::guard('company')->user()->company_id;
        $job->setRelation('company', Company::find($job->company_id));
        $job->setRelation('tags', Tag::whereIn('id', $request->input('tags', []))->get());

        // 関連求人(空)
        $companyJobs = collect();

        // プレビュー用フラグ
        $isPreview = true;

        return view('jobs.show', [
            'job' => $job,
            'companyJobs' => $companyJobs,
            'isPreview' => $isPreview,
        ]);
    }

    /**
     * 求人を複製して新規作成フォームに値を引き継ぐ
     *
     * @since 1.0.0
     *
     * @param int $id 求人ID
     * @return \Illuminate\View\View 新規求人作成ページのビュー
     */
    public function copy($id)
    {
        // 求人データを取得
        $job = Job::with('tags')->findOrFail($id);

        // 複製用に値を引き継いで新規作成フォームへ
        $allTags = Tag::orderBy('sort_order')->get();
        $categories = JobCategory::all();
        $locations = Location::all();
        $selectedTags = $job->tags->pluck('id')->toArray();

        return view('company.jobs.create', [
            'job' => $job,
            'allTags' => $allTags,
            'categories' => $categories,
            'locations' => $locations,
            'selectedTags' => $selectedTags,
            'isCopy' => true,
        ]);
    }

    /**
     * 求人の公開状態を切り替える
     *
     * @since 1.0.0
     *
     * @param int $id 求人ID
     * @return \Illuminate\Http\RedirectResponse 切り替え後のリダイレクトレスポンス
     */
    public function toggleActive($id)
    {
        // 求人データを取得
        $job = Job::findOrFail($id);
        $this->authorize('update', $job);

        // 公開にする時だけauto_reply_messageが未設定ならエラー
        if (!$job->is_active && empty($job->auto_reply_message)) {
            return back()->withErrors(['auto_reply_message' => '公開には自動メッセージの設定が必須です。'])
                ->withInput();
        }

        // 求人の公開状態を反転
        // is_activeが1なら0に、0なら1に切り替える
        $job->is_active = !$job->is_active;
        $job->save();

        return back()->with('success', '公開状態を変更しました');
    }

    /**
     * 求人を募集終了にする
     *
     * @since 1.0.0
     *
     * @param int $id 求人ID
     * @return \Illuminate\Http\RedirectResponse 募集終了後のリダイレクトレスポンス
     */
    public function close($id)
    {
        // 求人データを取得
        $job = Job::findOrFail($id);

        // 認可チェック
        $this->authorize('update', $job);

        // 募集終了の処理
        // is_closedを1に、is_activeを0に設定
        // application_deadlineを現在日時に設定
        $job->is_closed = 1;
        $job->is_active = 0;
        $job->application_deadline = now();
        $job->save();

        return back()->with('success', 'この求人を募集終了にしました');
    }

    /**
     * 応募者一覧を表示
     *
     * @since 1.0.0
     *
     * @param int $id 求人ID
     * @return \Illuminate\View\View 応募者一覧ページのビュー
     */
    public function applicants($id)
    {
        // 求人データを取得
        $job = Job::with('applications')->findOrFail($id);

        // 認可チェック
        $this->authorize('view', $job);

        // 応募者情報を取得
        // applicationsリレーションを使って応募者情報を取得
        $applicants = $job->applications()->with('user')->get();

        return view('company.jobs.applicants', [
            'job' => $job,
            'applicants' => $applicants,
        ]);
    }

    /**
     * 応募者のステータスを更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $id 求人ID
     * @return \Illuminate\Http\RedirectResponse ステータス更新後のリダイレクトレスポンス
     */
    public function status(Request $request, $id)
    {
        // 求人データを取得
        $job = Job::findOrFail($id);

        // 認可チェック
        $this->authorize('view', $job);

        // バリデーション
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'status' => 'required|string|in:applied,interviewed,offered,accepted,rejected',
        ]);

        // 応募者のステータスを更新
        $application = $job->applications()->findOrFail($validated['application_id']);
        $application->status = $validated['status'];
        $application->save();

        return back()->with('success', '応募者のステータスを更新しました');
    }

    /**
     * 応募者詳細を表示
     *
     * @since 1.0.0
     *
     * @param int $applicationId 応募ID
     * @return \Illuminate\View\View 応募者詳細ページのビュー
     */
    public function showApplicant($applicationId)
    {
        // 応募データを取得
        $application = Application::with('user', 'job')->findOrFail($applicationId);

        // 認可チェック
        $this->authorize('view', $application);

        return view('company.applications.show', [
            'application' => $application,
        ]);
    }

    /**
     * 求人のバリデーションルールを取得
     *
     * @since 1.0.0
     *
     * @return array バリデーションルールの配列
     */
    public function getValidationRules()
    {
        return [
            'title' => 'required|string|max:255',
            'job_category_id' => 'required|exists:job_categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'location' => 'nullable|string|max:255',
            'salary_min' => 'required|integer|min:0',
            'salary_max' => 'required|integer|min:0|gte:salary_min',
            'employment_type' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'welcome_skills' => 'nullable|string',
            'required_qualifications' => 'nullable|string',
            'tools' => 'nullable|string',
            'selection_flow' => 'nullable|string',
            'required_documents' => 'nullable|string',
            'interview_place' => 'nullable|string|max:255',
            'benefits' => 'nullable|string',
            'work_time' => 'nullable|string|max:255',
            'holiday' => 'nullable|string|max:255',
            'number_of_positions' => 'nullable|integer|min:1',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'auto_reply_message' => 'nullable|string',
        ];
    }
}