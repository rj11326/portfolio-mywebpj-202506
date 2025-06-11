<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminTagController extends Controller
{
    public function index()
    {
        $tags = Tag::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug',
            'sort_order' => ['nullable', 'integer'],
        ]);

        // sort_order未入力または重複時は自動で最大値+1
        if (empty($validated['sort_order']) || Tag::where('sort_order', $validated['sort_order'])->exists()) {
            $max = Tag::max('sort_order');
            $validated['sort_order'] = is_null($max) ? 1 : $max + 1;
        }

        Tag::create($validated);
        return redirect()->route('admin.tags.index')->with('success', 'タグを追加しました');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tags,slug,' . $tag->id,
            'sort_order' => ['nullable', 'integer'],
        ]);

        // sort_order未入力または重複時は自動で最大値+1
        if (empty($validated['sort_order']) || Tag::where('sort_order', $validated['sort_order'])->where('id', '!=', $tag->id)->exists()) {
            $max = Tag::max('sort_order');
            $validated['sort_order'] = is_null($max) ? 1 : $max + 1;
        }

        $tag->update($validated);
        return redirect()->route('admin.tags.index')->with('success', 'タグを更新しました');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'タグを削除しました');
    }
}