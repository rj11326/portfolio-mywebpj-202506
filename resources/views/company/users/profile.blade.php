{{-- resources/views/company/users/profile.blade.php --}}
@extends('layouts.company')

@section('title', '自分のプロフィール編集')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-4">自分のプロフィール編集</h1>

    <form action="{{ route('company.users.update_profile') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">氏名</label>
            <input type="text" name="name" class="form-input w-full" required value="{{ old('name', auth('company')->user()->name) }}">
            @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">パスワード（変更時のみ入力）</label>
            <input type="password" name="password" class="form-input w-full">
            @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
