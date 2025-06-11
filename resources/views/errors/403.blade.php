@extends('layouts.app')

@section('title', 'アクセス権限がありません')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="text-6xl font-bold text-red-500 my-6">403</div>
    <h1 class="text-2xl font-semibold mb-2">アクセス権限がありません</h1>
    <p class="mb-6 text-gray-600">このページへのアクセスは許可されていません。</p>
    <a href="{{ url()->previous() }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">前のページに戻る</a>
</div>
@endsection
