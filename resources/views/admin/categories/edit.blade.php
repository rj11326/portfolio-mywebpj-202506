@extends('layouts.admin')

@section('title', 'カテゴリ追加')

@section('content')
<div class="flex justify-center">
    <h1 class="text-2xl font-bold mb-6 w-full max-w-lg text-left">カテゴリ編集</h1>
</div>
<div class="flex justify-center items-center">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="bg-white p-6 rounded shadow max-w-lg w-full">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">カテゴリ名</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required value="{{ old('name', $category->name) }}">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">スラッグ</label>
            <input type="text" name="slug" class="w-full border rounded px-3 py-2" required value="{{ old('slug', $category->slug) }}">
            @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @if($category->children()->exists())
            {{-- 既に子カテゴリがある場合は親カテゴリ選択を非表示 --}}
            <input type="hidden" name="parent_id" value="">
        @else
        <div class="mb-4">
            <label class="block font-semibold mb-1">親カテゴリ</label>
            <p class="text-sm text-gray-500 mb-1">「-- なし --」を選択すると親カテゴリとなります。</p>
            <select name="parent_id" class="w-full border rounded px-3 py-2">
                <option value="">-- なし --</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" @if(old('parent_id', $category->parent_id) == $parent->id) selected @endif>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
            @error('parent_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif
        <div class="mb-4">
            <label class="block font-semibold mb-1">アイコン</label>
            <p class="text-sm text-gray-500 mb-1">ホーム画面のカテゴリで表示されるアイコンです。親カテゴリのみ表示されます。</p>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2" value="{{ old('icon', $category->icon) }}">
            @error('icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">更新</button>
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">戻る</a>
        </div>
    </form>
</div>
@endsection