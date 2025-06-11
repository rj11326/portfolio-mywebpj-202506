{{-- resources/views/saved-jobs/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-2xl font-bold mb-6">保存済み求人一覧</h1>
    @if($jobs->isEmpty())
        <div class="text-gray-400 text-center py-16">保存済み求人はありません。</div>
    @else
        <div class="space-y-6">
            @foreach($jobs as $job)
                <div class="bg-white shadow p-5 rounded-xl">
                    <div class="flex justify-between items-center mb-1">
                        <h2 class="font-semibold text-lg">{{ $job->title }}</h2>
                        @if($job->company)
                            <span class="text-sm text-gray-400">{{ $job->company->name }}</span>
                        @endif
                    </div>
                    <div class="text-gray-700 mb-2">{{ $job->description }}</div>
                    <div class="text-xs text-gray-500 flex gap-4">
                        <span>勤務地: {{ $job->location }}</span>
                        @if($job->salary_min && $job->salary_max)
                            <span>年収: {{ $job->salary_min }}万～{{ $job->salary_max }}万</span>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', ['job' => $job->id]) }}" class="inline-block mt-3 text-red-600 underline">詳細を見る</a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
