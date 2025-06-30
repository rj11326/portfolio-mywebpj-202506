@extends('layouts.company')

@section('title', '応募者詳細')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow px-8 py-8" x-data="{ tab: 'detail' }">

    {{-- タブ切り替え --}}
    <div class="flex border-b mb-8">
        <button :class="tab === 'detail' ? 'border-b-2 border-blue-600 text-blue-700 font-bold' : 'text-gray-600'"
            class="px-4 py-2 focus:outline-none transition" @click="tab = 'detail'">
            応募詳細
        </button>
        <button :class="tab === 'message' ? 'border-b-2 border-blue-600 text-blue-700 font-bold' : 'text-gray-600'"
            class="px-4 py-2 focus:outline-none transition" @click="
            tab = 'message';
            $nextTick(() => {
                let list = document.getElementById('messages-list');
                if (list) list.scrollTop = list.scrollHeight;
            });
        ">
            メッセージ
        </button>
    </div>

    {{-- 応募詳細タブ --}}
    <div x-show="tab === 'detail'" x-cloak>
        {{-- 応募者基本情報 --}}
        <h1 class="text-2xl font-bold mb-6">応募者詳細</h1>
        <div class="flex gap-8 mb-8 items-start">
            <div class="flex-1">
                <div class="mb-2">
                    <span class="font-semibold">応募者名：</span>
                    {{ $application->user->name ?? '---' }}
                </div>
                <div class="mb-2">
                    <span class="font-semibold">応募日：</span>
                    {{ $application->created_at->format('Y年n月j日 H:i') }}
                </div>
                <div class="mb-2">
                    <span class="font-semibold">応募求人：</span>
                    {{ $application->job->title ?? '---' }}
                </div>
                <div class="mb-2">
                    <span class="font-semibold">ステータス：</span>
                    <span class="inline-block px-2 py-1 rounded text-white bg-gray-500 text-sm">
                        {{ config('const.application_statuses')[$application->status] }}
                    </span>
                </div>
            </div>
            {{-- ステータス変更フォーム --}}
            <div>
                <form method="POST" action="{{ route('company.applications.status', $application->id) }}"
                    class="flex flex-col gap-2">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ $application->id }}">
                    <label class="block text-sm font-medium mb-1">ステータス更新</label>
                    <select name="status" class="form-select border rounded px-2 py-1 w-48">
                        @foreach(config('const.application_statuses') as $val => $label)
                        <option value="{{ $val }}" @selected($application->status === $val)>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded px-4 py-2 mt-2">
                        更新
                    </button>
                </form>
            </div>
        </div>

        {{-- プロフィール・履歴書 --}}
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">プロフィール／履歴書情報</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <div class="mb-2"><span class="font-semibold">メール：</span> {{ $application->user->email ?? '---' }}</div>
                <div class="mb-2"><span class="font-semibold">電話：</span> {{ $application->user->phone ?? '---' }}</div>
                <div class="mb-2"><span class="font-semibold">年齢：</span> {{ $application->user->age ?? '---' }}</div>
                <div class="mb-2"><span class="font-semibold">住所：</span> {{ $application->user->address ?? '---' }}
                </div>
            </div>
        </div>

        {{-- 志望動機・自己PR --}}
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">志望動機・自己PR</h2>
        <div class="mb-6 text-gray-800 whitespace-pre-line">
            {{ $application->motivation ?? '---' }}
        </div>
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">応募時メッセージ</h2>
        <div class="mb-6 text-gray-800 whitespace-pre-line">
            {{ $application->message ?? '---' }}
        </div>

        {{-- 学歴 --}}
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">学歴</h2>
        @if($application->user->educations && $application->user->educations->count())
        <ul class="list-disc ml-4 mb-6">
            @foreach($application->user->educations as $edu)
            <li>
                {{ $edu->school_name }}@if($edu->degree)（{{ $edu->degree }}）@endif
                @if($edu->major) - {{ $edu->major }} @endif
                @if($edu->start_date) 期間：{{ $edu->start_date }} @endif
                @if($edu->end_date) ～ {{ $edu->end_date }} @endif
                @if($edu->description) - {{ $edu->description }} @endif
            </li>
            @endforeach
        </ul>
        @else
        <p class="text-gray-500 mb-6">学歴情報はありません。</p>
        @endif

        {{-- 職歴 --}}
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">職歴</h2>
        @if($application->user->workHistories && $application->user->workHistories->count())
        @foreach($application->user->workHistories as $work)
        <div class="mb-6">
            <h3 class="font-semibold">{{ $work->job_title }}（{{ $work->company_name }}）</h3>
            <p class="text-gray-600 mb-1">
                期間：{{ $work->start_date }} ～ {{ $work->end_date ?? '現在' }}
                @if($work->is_current) <span class="text-green-500">(在籍中)</span> @endif
            </p>
            <p class="text-gray-800">{{ $work->description ?? '詳細はありません。' }}</p>
        </div>
        @endforeach
        @else
        <p class="text-gray-500 mb-6">職歴情報はありません。</p>
        @endif

        {{-- 資格 --}}
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">資格</h2>
        @if($application->user->licenses && $application->user->licenses->count())
        <ul class="list-disc ml-4 mb-6">
            @foreach($application->user->licenses as $lic)
            <li>
                {{ $lic->name }}（取得：{{ $lic->acquired_at }}）
                @if($lic->description) - {{ $lic->description }} @endif
            </li>
            @endforeach
        </ul>
        @else
        <p class="text-gray-500 mb-6">資格情報はありません。</p>
        @endif

        {{-- 添付ファイル --}}
        @if($application->files && $application->files->count())
        <h2 class="text-lg font-semibold mb-3 border-b pb-1">添付ファイル</h2>
        <ul class="mb-2">
            @foreach($application->files as $file)
            <li>
                <a href="{{ Storage::disk('public')->url($file->file_path) }}" class="text-blue-500 hover:underline"
                    target="_blank" download>
                    {{ $file->original_name ?? basename($file->file_path) }}
                </a>
            </li>
            @endforeach
        </ul>
        <a href="{{ route('company.applications.download_all', $application->id) }}"
            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2">
            すべてダウンロード（ZIP）
        </a>
        @endif

        {{-- 企業側メモ --}}
        <div class="mt-8">
            <form method="POST" action="{{ route('company.applications.memo', $application->id) }}">
                @csrf
                <label class="block font-semibold mb-2">担当者メモ</label>
                <textarea name="memo" rows="4"
                    class="form-textarea w-full rounded border px-2 py-1">{{ old('memo', $application->memo ?? '') }}</textarea>
                <button type="submit"
                    class="mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">保存</button>
            </form>
        </div>
    </div>

    {{-- メッセージタブ --}}
    <div x-show="tab === 'message'" x-cloak
        x-data="messageComponent({{ $application->id }}, '{{ addslashes($application->user->name) }}', 1, '/company/messages/')"
        x-init="init()" class="h-[600px] flex flex-col">
        <x-chat.box :headerTitle="$application->job->title ?? null" :mySenderType="1" />
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/chat.js') }}"></script>
@endpush