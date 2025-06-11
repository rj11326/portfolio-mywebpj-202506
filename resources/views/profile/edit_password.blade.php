@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow my-10">
    <h1 class="text-2xl font-bold mb-6">パスワード変更</h1>
    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold mb-1" for="current_password">現在のパスワード</label>
            <input type="password" name="current_password" id="current_password" class="w-full border rounded p-2" required>
            @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="password">新しいパスワード</label>
            <input type="password" name="password" id="password" class="w-full border rounded p-2" required>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="password_confirmation">新しいパスワード（確認）</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded font-semibold">
            パスワードを変更する
        </button>
    </form>
</div>
@endsection