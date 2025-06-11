<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    // 一覧表示
    public function index(Request $request)
    {
        $query = Job::query()->with(['company', 'location']);

        // キーワード
        if ($request->filled('keyword')) {
            $kw = $request->input('keyword');
            $query->where(function ($q) use ($kw) {
                $q->where('title', 'like', "%{$kw}%")
                    ->orWhere('description', 'like', "%{$kw}%");
            });
        }
        if ($request->filled('job_categories')) {
            $ids = array_filter(explode(',', $request->input('job_categories')));
            if (count($ids) > 0) {
                $query->whereIn('job_category_id', $ids);
            }
        }
        if ($request->filled('locations')) {
            $ids = array_filter(explode(',', $request->input('locations')));
            if (count($ids) > 0) {
                $query->whereIn('location_id', $ids);
            }
        }
        if ($request->filled('salary')) {
            $query->where('salary', '>=', (int) $request->input('salary') * 10000);
        }

        $jobs = $query->latest()->paginate(10)->appends($request->all());
        return view('jobs.index', compact('jobs'));
    }

    // 詳細表示
    public function show($id)
    {
        $job = Job::with([
            'company',
            'company.images' => function ($q) {
                $q->orderBy('order');
            }
        ])->findOrFail($id);

        $companyImages = optional($job->company)->images ?? collect();
        $imageUrls = $job->company ? $job->company->images()->orderBy('order')->get()->map(fn($i) => str_replace('\\', '/', Storage::disk('public')->url($i->file_path)))->values() : collect();
        $companyJobs = Job::where('company_id', $job->company_id)->where('id', '!=', $job->id)->where('is_active', true)->latest()->take(5)->get();

        return view('jobs.show', compact('job', 'companyJobs', 'companyImages', 'imageUrls'));
    }
}
