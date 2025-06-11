@extends('layouts.company')

@section('title', '担当者削除確認')

@section('content')
<div class="container py-6">
    <h1 class="text-2xl font-bold mb-4">担当者削除確認</h1>

    <div class="mb-6">
        <p>本当に <strong>{{ $user->name }}</strong>（{{ $user->email }}）さんを削除しますか？</p>
        <p class="text-red-600">※自分自身は削除できません。</p>
    </div>

    <form action="{{ route('company.users.destroy', $user->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">削除する</button>
        <a href="{{ route('company.users.index') }}" class="btn btn-secondary ml-2">キャンセル</a>
    </form>
</div>
@endsection
