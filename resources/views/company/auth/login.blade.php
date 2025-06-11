@extends('layouts.company')

@section('content')
<div class="max-w-md mx-auto mt-12 bg-white rounded-xl shadow p-8">
    <h1 class="text-2xl font-bold mb-6">企業ログイン</h1>
    @if(session('error'))
    <div class="mb-4 text-red-600">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('company.login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block font-medium">メールアドレス</label>
            <input type="email" name="email" id="email" required autofocus class="w-full border rounded px-4 py-2 mt-1"
                value="{{ old('email') }}">
        </div>
        <div class="mb-6">
            <label for="password" class="block font-medium">パスワード</label>
            <input type="password" name="password" id="password" required class="w-full border rounded px-4 py-2 mt-1">
        </div>
        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded font-bold">
            ログイン
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        企業アカウントをお持ちでない方は
        <a href="{{ route('company.apply.index') }}" class="text-blue-600 underline hover:text-blue-800">
            新規申請はこちら
        </a>
    </div>
</div>
@endsection