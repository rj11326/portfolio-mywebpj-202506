<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class JobApiController extends Controller
{
    /**
     * 求人一覧を取得
     *
     * @since 1.0.1 応募済みの求人IDを取得する機能を追加
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\JsonResponse 求人一覧のJSONレスポンス
     */
    public function index(Request $request)
    {
        // 求人情報を取得
        $query = Job::with(['company', 'tags']);

        $appliedJobIds = [];
        if (Auth::guard('web')->check()) {
            $userId = Auth::id();
            $appliedJobIds = Application::where('user_id', $userId)->pluck('job_id')->toArray();
        }

        // タグフィルタリング
        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('tags.id', $tags);
            });
        }
        // カテゴリフィルタリング
        if ($request->filled('job_categories')) {
            $ids = array_filter(explode(',', $request->job_categories));
            if (count($ids) > 0) {
                $query->whereIn('job_category_id', $ids);
            }
        }
        // 勤務地フィルタリング
        if ($request->filled('locations')) {
            $ids = array_filter(explode(',', $request->locations));
            if (count($ids) > 0) {
                $query->whereIn('location_id', $ids);
            }
        }
        // 給与フィルタリング
        if ($request->filled('salary')) {
            $query->where('salary_min', '>=', (int) $request->salary);
        }
        // キーワードフィルタリング
        // タイトル、説明、会社名に対するキーワード検索
        // 検索キーワードが指定されている場合、LIKE検索を行う
        // タイトル、説明、会社名のいずれかにキーワードが含まれる求人を取得
        // 検索キーワードはリクエストの 'q' パラメータから取得
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                    ->orWhereHas('company', fn($q2) => $q2->where('name', 'LIKE', "%$request->q%"));
            });
        }
        // 雇用形態フィルタリング
        if ($request->filled('employment_types')) {
            $types = explode(',', $request->employment_types);
            $query->whereIn('employment_type', $types);
        }

        // アクティブな求人のみ
        $query->where('is_active', true);

        // 応募締切が過ぎていない求人のみ
        $query->where(function ($q) {
            $q->whereNull('application_deadline')
                ->orWhere('application_deadline', '>=', now());
        });


        // ソート
        if ($request->input('sort') === 'salary') {
            $query->orderByDesc('salary_max');
        } else {
            $query->orderByDesc('created_at');
        }

        // ページネーション
        $perPage = 6;
        $jobs = $query->paginate($perPage);

        // 求人情報の整形
        // 各求人の情報を必要な形式に変換
        // ここでは、ID、タイトル、勤務地、企業名、給与範囲、雇用形態、説明、タグを含む配列に変換
        $jobs->getCollection()->transform(function ($job) {
            return [
                'id' => $job->id,
                'title' => $job->title,
                'location' => $job->location,
                'company_name' => $job->company->name ?? '',
                "salary_min" => $job->salary_min,
                "salary_max" => $job->salary_max,
                'employment_type' => config('const.employment_types')[$job->employment_type] ?? 'その他',
                'description' => Str::limit($job->description, 100),
                'tags' => $job->tags->pluck('label')->toArray(),
            ];
        });

        // JSONレスポンスを返す
        // 求人情報、現在のページ、最終ページ、総件数を含む
        return response()->json([
            'jobs' => $jobs->items(),
            'applied_job_ids' => $appliedJobIds,
            'current_page' => $jobs->currentPage(),
            'last_page' => $jobs->lastPage(),
            'total' => $jobs->total(),
        ]);
    }
}