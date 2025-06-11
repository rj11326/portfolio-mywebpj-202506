<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;

class JobCategoryApiController extends Controller
{
    public function index()
    {
        $categories = JobCategory::whereNull('parent_id')
            ->with('children:id,name,parent_id,slug')
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json(['categories' => $categories]);
    }
}