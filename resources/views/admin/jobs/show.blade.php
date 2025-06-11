@extends('layouts.admin')

@section('title', '求人詳細')

@section('content')
<h1 class="text-2xl font-bold mb-6">{{ $job->title }}</h1>

<div class="bg-white rounded-xl shadow p-6 mb-6">
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
        <div>
            <dt class="font-semibold text-gray-600">会社名</dt>
            <dd class="mb-2">{{ $job->company->name }}</dd>
        </div>
        <div>
            <dt class="font-semibold text-gray-600">公開状態</dt>
            <dd class="mb-2">
                @if($job->is_active)
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-semibold">公開中</span>
                @else
                    <span class="inline-block px-2 py-1 bg-gray-200 text-gray-600 rounded text-xs font-semibold">非公開</span>
                @endif
            </dd>
        </div>
        <div>
            <dt class="font-semibold text-gray-600">注目</dt>
            <dd class="mb-2">
                @if($job->is_featured)
                    <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold">注目</span>
                @else
                    <span class="inline-block px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs font-semibold">通常</span>
                @endif
            </dd>
        </div>
        <div>
            <dt class="font-semibold text-gray-600">募集状態</dt>
            <dd class="mb-2">
                @if($job->is_closed)
                    <span class="inline-block px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">募集終了</span>
                @else
                    <span class="inline-block px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-semibold">募集中</span>
                @endif
            </dd>
        </div>
        <div class="sm:col-span-2">
            <dt class="font-semibold text-gray-600">作成日</dt>
            <dd>{{ $job->created_at->format('Y/m/d H:i') }}</dd>
        </div>
    </dl>
</div>

<div class="flex flex-wrap gap-4 mb-8">
    <form method="POST" action="{{ route('admin.jobs.toggle_active', $job) }}">
        @csrf
        <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 transition"
            onclick="return confirm('公開状態を変更しますか？');">
            {{ $job->is_active ? '非公開にする' : '公開にする' }}
        </button>
    </form>
    <form method="POST" action="{{ route('admin.jobs.toggle_featured', $job) }}">
        @csrf
        <button class="px-4 py-2 rounded bg-yellow-400 text-white hover:bg-yellow-500 transition"
            onclick="return confirm('注目フラグを変更しますか？');">
            {{ $job->is_featured ? '注目解除' : '注目にする' }}
        </button>
    </form>
    <form method="POST" action="{{ route('admin.jobs.close', $job) }}">
        @csrf
        <button class="px-4 py-2 rounded bg-red-500 text-white hover:bg-red-600 transition"
            onclick="return confirm('この求人を募集終了にしますか？');">
            募集終了
        </button>
    </form>
</div>

<a href="{{ route('admin.jobs.index') }}" class="inline-block text-blue-600 hover:underline">&larr; 求人一覧に戻る</a>
@endsection