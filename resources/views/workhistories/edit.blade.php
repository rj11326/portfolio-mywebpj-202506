@extends('layouts.app')

@section('title', '職歴の編集')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4"
    x-data="{ isCurrent: {{ old('is_current', $workHistory->is_current ?? false) ? 'true' : 'false' }} }">
    {{-- 戻るリンク --}}
    <div class="mb-6">
        <a href="{{ route('mypage') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
            </svg>
            マイページへ戻る
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">職歴の編集</h1>

    <form action="{{ route('workhistories.update', $workHistory->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- 職種 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">職種</label>
            <input type="text" name="job_title"
                value="{{ old('job_title', $workHistory->job_title ?? $workHistory->title) }}"
                class="w-full border rounded px-3 py-2" required>
            @error('job_title')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 会社名 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">会社名</label>
            <input type="text" name="company_name"
                value="{{ old('company_name', $workHistory->company_name ?? $workHistory->company) }}"
                class="w-full border rounded px-3 py-2" required>
            @error('company_name')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 勤務地 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">勤務地</label>
            <input type="text" name="location" value="{{ old('location', $workHistory->location) }}"
                class="w-full border rounded px-3 py-2">
            @error('location')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- ポジション・役職 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">ポジション・役職</label>
            <input type="text" name="position" value="{{ old('position', $workHistory->position) }}"
                class="w-full border rounded px-3 py-2">
            @error('position')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 期間 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">期間</label>
            <div class="flex items-center gap-4 flex-wrap">
                <div>
                    <label class="block text-xs text-gray-500">開始日</label>
                    <input type="date" name="start_date"
                        value="{{ old('start_date', $workHistory->start_date ?? $workHistory->started_at) }}"
                        class="mt-1 block w-full border rounded px-3 py-2" required>
                    @error('start_date')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <template x-if="!isCurrent">
                    <div>
                        <label class="block text-xs text-gray-500">終了日（任意）</label>
                        <input type="date" name="end_date"
                            value="{{ old('end_date', $workHistory->end_date ?? $workHistory->ended_at) }}"
                            class="mt-1 block w-full border rounded px-3 py-2">
                        @error('end_date')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </template>
                <div class="flex items-center ml-2 mt-6">
                    <input type="hidden" name="is_current" value="0">
                    <input type="checkbox" id="is_current" name="is_current" class="mr-2" x-model="isCurrent" value="1"
                        {{ old('is_current') ? 'checked' : '' }}>
                    <label for="is_current" class="text-sm text-gray-700 select-none">在籍中</label>
                </div>
            </div>
        </div>

        <!-- 詳細説明 -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">詳細</label>
            <textarea name="description" rows="4"
                class="w-full border rounded px-3 py-2">{{ old('description', $workHistory->description) }}</textarea>
            @error('description')
            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-full transition">
            更新
        </button>
    </form>
</div>
@endsection