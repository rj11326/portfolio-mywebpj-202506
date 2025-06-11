@extends('layouts.company')

@section('title', '企業管理ダッシュボード')

@php
$user = auth('company')->user();
@endphp

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-6">企業ダッシュボード</h1>

    {{-- メッセージ表示 --}}
    @if(session('status'))
    <div class="mb-4 p-3 bg-green-100 border rounded text-green-800">
        {{ session('status') }}
    </div>
    @endif

    {{-- お知らせエリア --}}
    @if($latestApplications->count())
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded">
        <h2 class="font-semibold text-lg mb-2">お知らせ</h2>
        <ul class="space-y-1">
            @foreach($latestApplications as $app)
            <li>
                <span class="text-sm text-gray-600">
                    「{{ $app->job->title }}」に新しい応募がありました（{{ $app->created_at->format('Y/m/d H:i') }}）
                </span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if($user->role === 1)
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold mb-2">登録情報管理</h2>
            <ul class="text-sm text-gray-700">
                <li>
                    <a href="{{ route('company.profiles.show') }}" class="hover:underline">会社情報の編集</a>
                </li>
                <li>
                    <a href="{{ route('company.images') }}" class="hover:underline">会社画像の編集</a>
                </li>
                <li>
                    <a href="{{ route('company.users.index') }}" class="hover:underline">担当者管理</a>
                </li>
            </ul>
        </div>
        @endif
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold mb-2">求人管理</h2>
            <ul class="text-sm text-gray-700">
                <li>
                    <a href="{{ route('company.jobs.index') }}" class="hover:underline">求人一覧</a>
                </li>
                <li>
                    <a href="{{ route('company.jobs.create') }}" class="hover:underline">新規求人作成</a>
                </li>
            </ul>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="font-semibold mb-2">応募管理</h2>
            <ul class="text-sm text-gray-700">
                <li>
                    <a href="{{ route('company.applications.index') }}" class="hover:underline">応募一覧</a>
                </li>
            </ul>
        </div>
    </div>

</div>
@endsection