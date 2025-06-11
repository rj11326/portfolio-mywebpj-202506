@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold mb-6">応募履歴</h1>

    @if ($applications->isEmpty())
        <div class="text-gray-500">応募履歴はありません。</div>
    @else
        <div class="space-y-4">
            @foreach ($applications as $app)
                <div class="p-4 bg-white rounded shadow">
                    <div class="font-semibold">{{ $app->job->title }}</div>
                    <div class="text-sm text-gray-500 mb-1">
                        {{ $app->job->company->name ?? '非公開' }} / {{ $app->job->location }}
                    </div>
                    <div class="text-xs mb-1">応募日: {{ $app->created_at->format('Y/m/d') }}</div>
                    <div class="text-xs mb-1">選考状況: <span class="font-bold">{{ config('const.application_statuses')[$app->status] }}</span></div>
                    @if ($app->message)
                        <div class="text-sm mt-2 bg-gray-100 p-2 rounded">応募メッセージ: {{ $app->message }}</div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
