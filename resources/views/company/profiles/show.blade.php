@extends('layouts.company')

@section('title', '会社情報')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-8 text-gray-900">会社情報</h1>
    @if(session('status'))
    <div class="mb-4 p-3 bg-green-100 border rounded text-green-800 text-sm">
        {{ session('status') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow p-8">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                </svg>

                <span class="text-xl font-semibold text-gray-700">{{ $company->name }}</span>
            </div>
            <a href="{{ route('company.profiles.edit') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded transition">編集</a>
        </div>

        <div class="divide-y">
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">メールアドレス</span>
                <span class="text-gray-900">{{ $company->email }}</span>
            </div>
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">概要</span>
                <span class="text-gray-900 text-right">{{ $company->description ?: '—' }}</span>
            </div>
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">ウェブサイト</span>
                <span class="text-gray-900">
                    @if($company->website)
                    <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:underline">
                        {{ $company->website }}
                    </a>
                    @else
                    —
                    @endif
                </span>
            </div>
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">事業内容</span>
                <span class="text-gray-900">{{ $company->business ?: '—' }}</span>
            </div>
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">設立日</span>
                <span
                    class="text-gray-900">{{ $company->founded_at ? \Carbon\Carbon::parse($company->founded_at)->format('Y年n月d日') : '—' }}</span>
            </div>
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">資本金</span>
                <span
                    class="text-gray-900">{{ $company->capital ? number_format($company->capital) . ' 万円' : '—' }}</span>
            </div>
            <div class="flex justify-between py-4">
                <span class="text-gray-500 font-medium">従業員数</span>
                <span
                    class="text-gray-900">{{ $company->employee_count ? $company->employee_count . ' 人' : '—' }}</span>
            </div>
        </div>
    </div>
</div>
@endsection