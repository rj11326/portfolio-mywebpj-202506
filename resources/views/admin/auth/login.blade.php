@extends('layouts.admin')

@section('title', '管理者ログイン')

@section('content')
<div class="max-w-md mx-auto mt-12 bg-white rounded-xl shadow p-8">
    <h1 class="text-2xl font-bold mb-6">管理者ログイン</h1>
    @if(session('error'))
        <div class="mb-4 text-red-600">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block font-medium">メールアドレス</label>
            <input type="email" name="email" id="email" required autofocus class="w-full border rounded px-4 py-2 mt-1" value="{{ old('email') }}">
        </div>
        <div class="mb-6">
            <label for="password" class="block font-medium">パスワード</label>
            <input type="password" name="password" id="password" required class="w-full border rounded px-4 py-2 mt-1">
        </div>
        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2 rounded font-bold">
            ログイン
        </button>
    </form>
</div>
@endsection
