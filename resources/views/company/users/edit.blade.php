{{-- resources/views/company/users/edit.blade.php --}}
@extends('layouts.company')

@section('title', '担当者編集')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-4">担当者編集</h1>

    <form action="{{ route('company.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-semibold">氏名</label>
            <input type="text" name="name" class="form-input w-full" required value="{{ old('name', $user->name) }}">
            @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">メールアドレス</label>
            <input type="email" name="email" class="form-input w-full" required value="{{ old('email', $user->email) }}">
            @error('email') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">ロール</label>
            <select name="role" class="form-select w-full" required>
                <option value="1" {{ old('role', $user->role) === 1 ? 'selected' : '' }}>admin（管理者）</option>
                <option value="2" {{ old('role', $user->role) === 2 ? 'selected' : '' }}>member（編集専用）</option>
            </select>
            @error('role') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('company.users.index') }}" class="btn btn-secondary ml-2">キャンセル</a>
    </form>
</div>
@endsection
