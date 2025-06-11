<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = $request->user()->savedJobs()->with('company')->get();
        return view('saved_jobs.index', compact('jobs'));
    }

}
