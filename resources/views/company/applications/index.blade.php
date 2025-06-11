@extends('layouts.company')

@section('title', '応募一覧')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">応募者一覧</h1>

    <!-- 検索/フィルタ -->
    <form method="GET" class="flex flex-wrap items-end gap-4 mb-8">
        <div>
            <label class="block text-sm mb-1">求人タイトル</label>
            <input type="text" name="job_title" value="{{ old('job_title', $jobTitle) }}" class="form-input w-48">
        </div>
        <div>
            <label class="block text-sm mb-1">求人（プルダウン）</label>
            <select name="job_id" class="form-select w-48">
                <option value="">-- すべて --</option>
                @foreach($jobs as $job)
                    <option value="{{ $job->id }}" @selected($jobIdFilter==$job->id)>{{ $job->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm mb-1">応募者名</label>
            <input type="text" name="user_name" value="{{ old('user_name', $userName) }}" class="form-input w-40">
        </div>
        <div>
            <label class="block text-sm mb-1">ステータス</label>
            <select name="status" class="form-select w-36">
                <option value="">-- すべて --</option>
                @foreach($statuses as $k => $v)
                    <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="bg-blue-600 text-white rounded px-5 py-2 hover:bg-blue-700">検索</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-xl shadow">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">応募者</th>
                    <th class="py-2 px-4 border-b">求人タイトル</th>
                    <th class="py-2 px-4 border-b">応募日</th>
                    <th class="py-2 px-4 border-b">ステータス</th>
                    <th class="py-2 px-4 border-b"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td class="py-2 px-4 border-b">{{ $app->user->name }}</td>
                    <td class="py-2 px-4 border-b">{{ $app->job->title }}</td>
                    <td class="py-2 px-4 border-b">{{ $app->created_at->format('Y-m-d') }}</td>
                    <td class="py-2 px-4 border-b">
                        {{ $statuses[$app->status] ?? $app->status }}
                    </td>
                    <td class="py-2 px-4 border-b">
                        <a href="{{ route('company.applications.show', $app->id) }}" class="text-blue-500 hover:underline">詳細</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-4 text-center text-gray-500">該当する応募がありません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $applications->links() }}
    </div>
</div>
@endsection
