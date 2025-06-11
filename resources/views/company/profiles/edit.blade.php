@extends('layouts.company')

@section('title', '会社情報編集')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-8">会社情報編集</h1>
    <form action="{{ route('company.profiles.update') }}" method="POST" class="bg-white rounded-xl shadow p-6 space-y-6">
        @csrf
        @method('POST')

        <div>
            <label class="block font-semibold mb-1">会社名</label>
            <input type="text" name="name" value="{{ old('name', $company->name) }}" class="w-full border rounded px-3 py-2" required>
            @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">メールアドレス</label>
            <input type="email" name="email" value="{{ old('email', $company->email) }}" class="w-full border rounded px-3 py-2" required>
            @error('email') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">概要</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="3">{{ old('description', $company->description) }}</textarea>
            @error('description') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">設立日</label>
            <input type="date" name="founded_at" value="{{ old('founded_at', $company->founded_at) }}" class="w-full border rounded px-3 py-2">
            @error('founded_at') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">資本金</label>
            <input type="number" name="capital" value="{{ old('capital', $company->capital) }}" class="w-full border rounded px-3 py-2">
            @error('capital') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block font-semibold mb-1">従業員数</label>
            <input type="number" name="employee_count" value="{{ old('employee_count', $company->employee_count) }}" class="w-full border rounded px-3 py-2">
            @error('employee_count') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded transition">
                保存
            </button>
        </div>
    </form>
</div>
@endsection
