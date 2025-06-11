@extends('layouts.app')

@section('title', '応募完了')

@section('content')
<div class="max-w-xl mx-auto py-10 text-center">
    <h1 class="text-2xl font-bold mb-6 text-green-600">ご応募ありがとうございます！</h1>
    <p class="text-gray-700 mb-4">応募内容を受け付けました。<br>担当者よりご連絡いたしますのでお待ちください。</p>
    <a href="{{ route('jobs.index') }}" class="inline-block mt-6 px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full font-semibold">
        求人一覧へ戻る
    </a>
</div>
@endsection
