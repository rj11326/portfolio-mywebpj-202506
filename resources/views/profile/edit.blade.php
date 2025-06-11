@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow my-10">
    <h1 class="text-2xl font-bold mb-6">プロフィール編集</h1>
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
    <form method="POST" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-semibold mb-1" for="name">氏名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}"
                class="w-full border rounded p-2" required>
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}"
                class="w-full border rounded p-2" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="phone">電話番号</label>
            <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                class="w-full border rounded p-2">
            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', $user->address ?? '') }}"
                class="w-full border rounded p-2">
            @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-right mt-6">
            <a href="{{ route('password.edit') }}" class="text-red-500 hover:underline font-semibold">
                パスワードを変更する
            </a>
        </div>

        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded font-semibold">
            更新する
        </button>
    </form>
</div>
@endsection