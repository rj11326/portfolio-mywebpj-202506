@extends('layouts.company')
@section('title', '求人一覧')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">求人一覧</h1>

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 rounded px-4 py-3 text-green-900">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->has('auto_reply_message'))
    <div class="mb-4 bg-red-100 border border-red-300 rounded px-4 py-3 text-red-900">
        {{ $errors->first('auto_reply_message') }}
    </div>
    @endif

    <div class="mb-4 text-right">
        <a href="{{ route('company.jobs.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">新規求人作成</a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded shadow text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-2 px-3">タイトル</th>
                    <th class="py-2 px-3">公開</th>
                    <th class="py-2 px-3">状態</th>
                    <th class="py-2 px-3">応募数</th>
                    <th class="py-2 px-3">締切</th>
                    <th class="py-2 px-3">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jobs as $job)
                <tr>
                    <td class="border-t px-3 py-2">{{ $job->title }}</td>
                    <td class="border-t px-3 py-2">
                        @if($job->is_active)
                        <span class="text-green-700 font-bold">公開中</span>
                        @else
                        <span class="text-gray-400">非公開</span>
                        @endif
                    </td>
                    <td class="border-t px-3 py-2">
                        @if($job->is_closed == 0)
                        <span class="text-blue-600 font-bold">募集中</span>
                        @else
                        <span class="text-red-600">募集終了</span>
                        @endif
                    </td>
                    <td class="border-t px-3 py-2">
                        <a href="{{ route('company.applications.index', ['job_id' => $job->id]) }}"
                            class="underline text-blue-600">
                            {{ $job->applications()->count() }}
                        </a>
                    </td>
                    <td class="border-t px-3 py-2">
                        {{ $job->application_deadline ? \Carbon\Carbon::parse($job->application_deadline)->format('Y/m/d') : '-' }}
                    </td>
                    <td class="border-t px-3 py-2 flex flex-wrap gap-2">
                        <a href="{{ route('company.jobs.edit', $job) }}" class="text-indigo-600 hover:underline">編集</a>
                        <a href="{{ route('company.jobs.copy', $job) }}" class="text-blue-600 hover:underline">複製</a>
                        <form action="{{ route('company.jobs.toggle_active', $job) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:underline"
                                onclick="return confirm('この求人を{{ $job->is_active ? '非公開' : '公開' }}にしますか？');">
                                {{ $job->is_active ? '非公開にする' : '公開にする' }}
                            </button>
                        </form>
                        <form action="{{ route('company.jobs.close', $job) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline"
                                onclick="return confirm('この求人を募集終了にしますか？');">
                                募集終了
                            </button>
                        </form>
                        <form action="{{ route('company.jobs.destroy', $job) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-500 hover:underline"
                                onclick="return confirm('本当に削除しますか？');">
                                削除
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-400 py-8">求人がありません</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection