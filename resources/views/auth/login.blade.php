@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-white px-2">
    <div class="w-3xl max-w-sm space-y-6 p-6 bg-white border border-gray-200 rounded-xl shadow-md">
        <div class="text-center">
            <h2 class="text-xl font-bold text-gray-900">ログイン</h2>
            <p class="text-xs text-gray-500">アカウントにサインインしてください</p>
        </div>

        @if (session('status'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-sm">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
                <input id="email" name="email" type="email" required autofocus
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 border-gray-300">
                @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">パスワード</label>
                <input id="password" name="password" type="password" required
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 border-gray-300">
                @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded">
                    <span>ログイン状態を保持する</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-red-500 hover:underline">
                    パスワードを忘れた場合
                </a>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-full transition">
                    ログインする
                </button>
            </div>

            @if(request('redirect'))
            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
            @endif
        </form>

        <!-- 登録誘導 -->
        <p class="text-center text-sm text-gray-600">
            アカウントをお持ちでない方は
            <a href="{{ route('register') }}" class="text-red-500 hover:underline">新規登録</a>
        </p>
    </div>
</div>
@endsection