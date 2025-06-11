{{-- resources/views/company/users/create.blade.php --}}
@extends('layouts.company')

@section('title', '担当者新規追加')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-4">担当者新規追加</h1>

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 border rounded text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('company.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">氏名</label>
            <input type="text" name="name" class="form-input w-full" required value="{{ old('name') }}">
            @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">メールアドレス</label>
            <input type="email" name="email" class="form-input w-full" required value="{{ old('email') }}">
            @error('email') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">ロール</label>
            <select name="role" class="form-select w-full" required>
                <option value="member" {{ old('role') === 'member' ? 'selected' : '' }}>member（編集専用）</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>admin（管理者）</option>
            </select>
            @error('role') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-6">
            <label class="block mb-1 font-semibold">パスワード</label>
            <input type="password" name="password" class="form-input w-full">
            <small class="text-gray-500">未入力の場合は自動発行され、メールで通知されます。</small>
            @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">登録</button>
        <a href="{{ route('company.users.index') }}" class="btn btn-secondary ml-2">キャンセル</a>
    </form>
</div>
@endsection
