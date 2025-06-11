<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobCategory;

class HomeController extends Controller
{
    public function index()
    {
        $mainCategories = JobCategory::whereNull('parent_id')->get();

        $featuredJobs = Job::where('is_featured', true)->inRandomOrder()->take(3)->get();
        return view('home', [
            'mainCategories' => $mainCategories,
            'featuredJobs' => $featuredJobs,
        ]);
    }
}
