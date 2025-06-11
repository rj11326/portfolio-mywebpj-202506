@extends('layouts.admin')

@section('title', '求人一覧')

@section('content')
<h1 class="text-2xl font-bold mb-6">求人一覧</h1>

@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 rounded px-4 py-3 text-green-900">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto">
    <table class="min-w-full bg-white border rounded shadow text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="py-2 px-3">会社名</th>
                <th class="py-2 px-3">タイトル</th>
                <th class="py-2 px-3">公開</th>
                <th class="py-2 px-3">注目</th>
                <th class="py-2 px-3">募集状態</th>
                <th class="py-2 px-3">作成日</th>
                <th class="py-2 px-3">操作</th>
            </tr>
        </thead>
        <tbody>
        @forelse($jobs as $job)
            <tr>
                <td class="border-t px-3 py-2">{{ $job->company->name }}</td>
                <td class="border-t px-3 py-2">
                    <a href="{{ route('admin.jobs.show', $job) }}" class="text-blue-600 hover:underline">{{ $job->title }}</a>
                </td>
                <td class="border-t px-3 py-2">
                    @if($job->is_active)
                        <span class="text-green-700 font-bold">公開中</span>
                    @else
                        <span class="text-gray-400">非公開</span>
                    @endif
                </td>
                <td class="border-t px-3 py-2 text-center">
                    @if($job->is_featured)
                        <span class="text-yellow-500 text-lg">★</span>
                    @endif
                </td>
                <td class="border-t px-3 py-2">
                    @if($job->is_closed)
                        <span class="text-red-600 font-bold">募集終了</span>
                    @else
                        <span class="text-green-600">募集中</span>
                    @endif
                </td>
                <td class="border-t px-3 py-2">
                    {{ $job->created_at->format('Y/m/d') }}
                </td>
                <td class="border-t px-3 py-2 flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.jobs.toggle_active', $job) }}" class="inline">
                        @csrf
                        <button class="text-sm text-blue-600 hover:underline" onclick="return confirm('公開状態を変更しますか？');">
                            {{ $job->is_active ? '非公開' : '公開' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.jobs.toggle_featured', $job) }}" class="inline">
                        @csrf
                        <button class="text-sm text-yellow-600 hover:underline" onclick="return confirm('注目フラグを変更しますか？');">
                            {{ $job->is_featured ? '注目解除' : '注目' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.jobs.close', $job) }}" class="inline">
                        @csrf
                        <button class="text-sm text-red-600 hover:underline" onclick="return confirm('この求人を募集終了にしますか？');">
                            募集終了
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-gray-400 py-8">求人がありません</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $jobs->links() }}
    </div>
</div>
@endsection