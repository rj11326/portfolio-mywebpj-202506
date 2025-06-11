@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6">カテゴリ一覧</h1>
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 rounded px-4 py-3 text-green-900">
        {{ session('success') }}
    </div>
@endif
<div class="mb-4 text-right">
    <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">新規追加</a>
</div>
<table class="min-w-full bg-white border rounded shadow text-sm">
    <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-3">ID</th>
            <th class="py-2 px-3">カテゴリ名</th>
            <th class="py-2 px-3">スラッグ</th>
            <th class="py-2 px-3">親カテゴリ</th>
            <th class="py-2 px-3">操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
        <tr>
            <td class="border-t px-3 py-2">{{ $category->id }}</td>
            <td class="border-t px-3 py-2">{{ $category->name }}</td>
            <td class="border-t px-3 py-2">{{ $category->slug }}</td>
            <td class="border-t px-3 py-2">{{ $category->parent?->name ?? '-' }}</td>
            <td class="border-t px-3 py-2 flex gap-2">
                <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">編集</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline">削除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection