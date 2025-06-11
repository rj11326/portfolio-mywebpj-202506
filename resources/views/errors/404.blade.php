@extends('layouts.app')

@section('title', 'ページが見つかりません')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[60vh]">
    <div class="text-6xl font-bold text-yellow-400 mb-4">404</div>
    <h1 class="text-2xl font-semibold mb-2">ページが見つかりません</h1>
    <p class="mb-6 text-gray-600">お探しのページは存在しないか、削除された可能性があります。</p>
    <a href="{{ route('home') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">トップに戻る</a>
</div>
@endsection
