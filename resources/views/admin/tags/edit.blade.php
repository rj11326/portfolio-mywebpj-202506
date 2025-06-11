@extends('layouts.admin')

@section('content')
<div class="flex justify-center">
    <h1 class="text-2xl font-bold mb-6 w-full max-w-lg text-left">タグ編集</h1>
</div>
<div class="flex justify-center items-center">
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="bg-white p-6 rounded shadow max-w-lg w-full">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">ラベル</label>
            <input type="text" name="label" class="w-full border rounded px-3 py-2" required value="{{ old('label', $tag->label) }}">
            @error('label') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">スラッグ</label>
            <input type="text" name="slug" class="w-full border rounded px-3 py-2" required value="{{ old('slug', $tag->slug) }}">
            @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">表示順</label>
            <input type="number" name="sort_order" class="w-full border rounded px-3 py-2" value="{{ old('sort_order', $tag->sort_order) }}">
            @error('sort_order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">更新</button>
        <a href="{{ route('admin.tags.index') }}" class="ml-4 px-6 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">戻る</a>
    </form>
</div>
@endsection