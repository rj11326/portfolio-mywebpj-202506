<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Str;

class JobApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company', 'tags']);

        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            $query->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('tags.id', $tags);
            });
        }
        if ($request->filled('job_categories')) {
            $ids = array_filter(explode(',', $request->job_categories));
            if (count($ids) > 0) {
                $query->whereIn('job_category_id', $ids);
            }
        }
        if ($request->filled('locations')) {
            $ids = array_filter(explode(',', $request->locations));
            if (count($ids) > 0) {
                $query->whereIn('location_id', $ids);
            }
        }
        if ($request->filled('salary')) {
            $query->where('salary_min', '>=', (int) $request->salary);
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                    ->orWhereHas('company', fn($q2) => $q2->where('name', 'LIKE', "%$request->q%"));
            });
        }
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

        return response()->json([
            'jobs' => $jobs->items(),
            'current_page' => $jobs->currentPage(),
            'last_page' => $jobs->lastPage(),
            'total' => $jobs->total(),
        ]);
    }
}