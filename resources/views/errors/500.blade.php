@extends('layouts.app')

@section('title', 'サーバーエラー')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="text-6xl font-bold text-gray-600 mb-4">500</div>
    <h1 class="text-2xl font-semibold mb-2">サーバーエラーが発生しました</h1>
    <p class="mb-6 text-gray-600">しばらくしてから再度お試しください。</p>
    <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">トップに戻る</a>
</div>
@endsection
