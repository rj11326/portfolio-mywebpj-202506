@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto py-10 px-4"
    x-data="{ isCurrent: {{ old('is_current', $education->is_current ?? false) ? 'true' : 'false' }} }">
    {{-- 戻るリンク --}}
    <div class="mb-6">
        <a href="{{ route('mypage') }}" class="inline-flex items-center text-gray-600 hover:text-red-600 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
            </svg>
            マイページへ戻る
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">資格</h1>

    <form action="{{ route('licenses.store') }}" method="POST">
        @csrf

        <!-- 資格名 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">資格名</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
            @error('name')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <!-- 取得日 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">取得日</label>
            <input type="date" name="acquired_date" value="{{ old('acquired_date') }}"
                class="w-full border rounded px-3 py-2">
            @error('acquired_date')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <!-- ボタン -->
        <div class="flex justify-end">
            <button type="submit"
                class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-full transition">
                登録する
            </button>
        </div>
    </form>
</div>
@endsection