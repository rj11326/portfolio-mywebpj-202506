<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\Request;

class AdminJobCategoryController extends Controller
{
    public function index()
    {
        $categories = JobCategory::with('parent')->orderBy('id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = JobCategory::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_categories,slug',
            'parent_id' => 'nullable|exists:job_categories,id',
            'icon' => 'nullable|string|max:255',
        ]);
        JobCategory::create($validated);
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを追加しました');
    }

    public function edit(JobCategory $category)
    {
        $parents = JobCategory::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, JobCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:job_categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:job_categories,id',
            'icon' => 'nullable|string|max:255',
        ]);
        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを更新しました');
    }

    public function destroy(JobCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを削除しました');
    }
}