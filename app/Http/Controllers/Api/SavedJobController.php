<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class SavedJobController extends Controller
{
    public function listIds(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['saved_job_ids' => []]);
        }
        $ids = $user->savedJobs()->pluck('jobs.id');
        return response()->json(['saved_job_ids' => $ids]);
    }

    public function toggle(Request $request, Job $job)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['result' => 'unauthenticated'], 401);
        }
        if ($user->savedJobs()->where('job_id', $job->id)->exists()) {
            $user->savedJobs()->detach($job->id);
            return response()->json(['result' => 'removed']);
        } else {
            $user->savedJobs()->attach($job->id);
            return response()->json(['result' => 'saved']);
        }
    }
}
