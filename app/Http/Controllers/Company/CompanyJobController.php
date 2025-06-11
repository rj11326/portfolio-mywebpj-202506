<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Tag;
use App\Models\JobCategory;
use App\Models\Location;
use App\Models\Application;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CompanyJobController extends Controller
{
    use AuthorizesRequests;
    // 一覧表示
    public function index(Request $request)
    {
        $companyId = Auth::guard('company')->user()->company_id;
        $jobs = Job::with(['tags', 'jobCategory', 'location'])
            ->where('company_id', $companyId)
            ->latest()
            ->paginate(20);

        return view('company.jobs.index', compact('jobs'));
    }

    // 新規作成フォーム
    public function create()
    {

        $allTags = Tag::orderBy('sort_order')->get();
        $categories = JobCategory::all();
        $locations = Location::all();

        return view('company.jobs.create', [
            'allTags' => $allTags,
            'categories' => $categories,
            'locations' => $locations,
            'selectedTags' => [],
            'job' => null,
        ]);
    }

    // 登録処理
    public function store(Request $request)
    {
        $validated = $request->validate($this->getValidationRules());

        $validated['company_id'] = Auth::guard('company')->user()->company_id;
        $job = Job::create($validated);

        // タグ紐付け
        $job->tags()->sync($request->input('tags', []));

        return redirect()->route('company.jobs.index')->with('success', '求人を作成しました');
    }

    // 編集フォーム
    public function edit($id)
    {
        $job = Job::with('tags')->findOrFail($id);

        $this->authorize('update', $job);

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

    // 更新処理
    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $this->authorize('update', $job);
        $validated = $request->validate($this->getValidationRules());
        $job->update($validated);
        $job->tags()->sync($request->input('tags', []));

        return redirect()->route('company.jobs.index')->with('success', '求人を更新しました');
    }

    // プレビュー（入力値をViewで再利用するために使う）
    public function preview(Request $request)
    {
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

    // 複製（コピー）機能
    public function copy($id)
    {
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

    // 公開/非公開切り替え
    public function toggleActive($id)
    {
        $job = Job::findOrFail($id);
        $this->authorize('update', $job);

        $job->is_active = !$job->is_active;
        $job->save();

        return back()->with('success', '公開状態を変更しました');
    }

    // 募集終了処理
    public function close($id)
    {
        $job = Job::findOrFail($id);
        $this->authorize('update', $job);

        $job->is_closed = 1;
        $job->is_active = 0;
        $job->application_deadline = now();
        $job->save();

        return back()->with('success', 'この求人を募集終了にしました');
    }

    // 応募者一覧
    public function applicants($id)
    {
        $job = Job::with('applications')->findOrFail($id);
        $this->authorize('view', $job);
        $applicants = $job->applications()->with('user')->get();

        return view('company.jobs.applicants', [
            'job' => $job,
            'applicants' => $applicants,
        ]);
    }
    // 応募者ステータス変更
    public function status(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $this->authorize('view', $job);

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
    // 応募者詳細
    public function showApplicant($applicationId)
    {
        $application = Application::with('user', 'job')->findOrFail($applicationId);
        $this->authorize('view', $application);

        return view('company.applications.show', [
            'application' => $application,
        ]);
    }

    // バリデーションルール
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
        ];
    }
}

