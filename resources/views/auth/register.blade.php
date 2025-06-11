@extends('layouts.app')

@section('title', 'アカウント新規登録')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-lg bg-white p-10">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">アカウント作成</h2>
        <p class="text-sm text-center text-gray-500 mb-6">必要な情報を入力してください</p>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- 名前 -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">お名前</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- メールアドレス -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">メールアドレス</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">パスワード</label>
                <input id="password" name="password" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                @error('password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- パスワード確認 -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">パスワード確認</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
            </div>

            <!-- 登録ボタン -->
            <div>
                <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-full transition shadow">
                    登録する
                </button>
            </div>
        </form>

        <p class="text-sm text-center text-gray-600 mt-6">
            すでにアカウントをお持ちの方は
            <a href="{{ route('login') }}" class="text-red-500 hover:underline font-medium">ログイン</a>
        </p>
    </div>
</div>
@endsection
