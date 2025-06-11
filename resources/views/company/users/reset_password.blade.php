@extends('layouts.company')

@section('title', 'パスワードリセット')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-4">パスワードリセット</h1>

    <form action="{{ route('company.users.reset_password', $user->id) }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">新しいパスワード</label>
            <input type="password" name="password" class="form-input w-full" required>
            @error('password') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary">リセット</button>
        <a href="{{ route('company.users.index') }}" class="btn btn-secondary ml-2">キャンセル</a>
    </form>
</div>
@endsection
