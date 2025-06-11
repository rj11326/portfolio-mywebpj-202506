@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">管理者ダッシュボード</h1>
    <div class="bg-white p-6 rounded-xl shadow mb-8">
        <p>ようこそ、<span class="font-bold">{{ Auth::guard('admin')->user()->name }}</span>さん！</p>
        <p class="mt-4 text-gray-700">ここから企業申請の承認、カテゴリ・職種の管理、ユーザー管理などが行えます。</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <a href="{{ route('admin.company_applications.index') }}"
           class="block bg-gray-100 hover:bg-blue-100 rounded-xl p-6 shadow transition">
            <div class="text-lg font-semibold mb-2">企業申請一覧</div>
            <div class="text-gray-500 text-sm">新規申請や承認作業</div>
        </a>
        <a href="{{ route('admin.companies.index') }}"
           class="block bg-gray-100 hover:bg-blue-100 rounded-xl p-6 shadow transition">
            <div class="text-lg font-semibold mb-2">企業管理</div>
            <div class="text-gray-500 text-sm">企業の閲覧・編集</div>
        </a>
        <a href="{{ route('admin.jobs.index') }}"
           class="block bg-gray-100 hover:bg-blue-100 rounded-xl p-6 shadow transition">
            <div class="text-lg font-semibold mb-2">求人管理</div>
            <div class="text-gray-500 text-sm">求人の閲覧・編集</div>
        </a>
        <a href="{{ route('admin.categories.index') }}"
           class="block bg-gray-100 hover:bg-blue-100 rounded-xl p-6 shadow transition">
            <div class="text-lg font-semibold mb-2">カテゴリ管理</div>
            <div class="text-gray-500 text-sm">職種やカテゴリの追加・編集</div>
        </a>
        <a href="{{ route('admin.tags.index') }}"
           class="block bg-gray-100 hover:bg-blue-100 rounded-xl p-6 shadow transition">
            <div class="text-lg font-semibold mb-2">タグ管理</div>
            <div class="text-gray-500 text-sm">タグの追加・編集</div>
        </a>
    </div>
@endsection
