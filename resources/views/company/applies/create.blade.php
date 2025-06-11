@extends('layouts.company')

@section('content')
<div class="max-w-lg mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">企業アカウント申請フォーム</h1>

    @if(session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('company.apply.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block font-bold mb-1">会社名</label>
            <input type="text" name="company_name" class="w-full border rounded px-3 py-2" value="{{ old('company_name') }}">
            @error('company_name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">会社メールアドレス</label>
            <input type="email" name="company_email" class="w-full border rounded px-3 py-2" value="{{ old('company_email') }}">
            @error('company_email') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">会社説明</label>
            <textarea name="company_description" class="w-full border rounded px-3 py-2">{{ old('company_description') }}</textarea>
            @error('company_description') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">担当者氏名</label>
            <input type="text" name="contact_name" class="w-full border rounded px-3 py-2" value="{{ old('contact_name') }}">
            @error('contact_name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-bold mb-1">担当者メールアドレス</label>
            <input type="email" name="contact_email" class="w-full border rounded px-3 py-2" value="{{ old('contact_email') }}">
            @error('contact_email') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <div class="mb-6">
            <label class="block font-bold mb-1">担当者電話番号</label>
            <input type="text" name="contact_phone" class="w-full border rounded px-3 py-2" value="{{ old('contact_phone') }}">
            @error('contact_phone') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">申請する</button>
    </form>
</div>
@endsection
