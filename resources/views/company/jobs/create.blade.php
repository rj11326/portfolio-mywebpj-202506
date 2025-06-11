@extends('layouts.company')
@section('title', '新規求人作成')

@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-6">新規求人作成</h1>
    @include('company.jobs.form')
    <div class="mt-4">
        <a href="{{ route('company.jobs.index') }}" class="text-blue-600 hover:underline">← 求人一覧に戻る</a>
    </div>
</div>
@endsection
