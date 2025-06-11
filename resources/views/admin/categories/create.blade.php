@extends('layouts.admin')

@section('title', 'カテゴリ追加')

@section('content')
<div class="flex justify-center">
    <h1 class="text-2xl font-bold mb-6 w-full max-w-lg text-left">カテゴリ追加</h1>
</div>
<div class="flex justify-center items-center">
    <form method="POST" action="{{ route('admin.categories.store') }}"
        class="bg-white p-6 rounded shadow max-w-lg w-full">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-1">カテゴリ名</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">スラッグ</label>
            <input type="text" name="slug" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">親カテゴリ</label>
            <p class="text-sm text-gray-500 mb-1">「-- なし --」を選択すると親カテゴリとなります。</p>
            <select name="parent_id" class="w-full border rounded px-3 py-2">
                <option value="">-- なし --</option>
                @foreach($parents as $parent)
                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">アイコン</label>
            <p class="text-sm text-gray-500 mb-1">ホーム画面のカテゴリで表示されるアイコンです。親カテゴリのみ表示されます。</p>
            <input type="text" name="icon" class="w-full border rounded px-3 py-2">
        </div>
        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">追加</button>
            <a href="{{ route('admin.categories.index') }}"
                class="px-6 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">戻る</a>
        </div>
    </form>
</div>
@endsection