@extends('layouts.app')

@section('content')
<div class="flex justify-center items-start min-h-[80vh] bg-gray-100 py-6" x-data="messageComponent(
                {{ $selectedThread ? $selectedThread->id : 'null' }},
                '{{ $selectedThread ? addslashes($selectedThread->job->company->name) : '' }}',
                0, {{-- senderType --}}
                '/messages/' {{-- apiBase --}}
            )" x-init="init()">
    {{-- スレッド一覧 --}}
    <div class="w-80 bg-white rounded-lg shadow-md overflow-y-auto mr-6 flex-shrink-0" style="height: 600px;">
        <div class="p-4 font-bold text-lg border-b">メッセージ</div>
        <ul>
            @foreach($threads as $thread)
            <li class="p-4 border-b cursor-pointer hover:bg-blue-50"
                :class="selectedThreadId === {{ $thread->id }} ? 'bg-blue-100' : ''"
                @click="switchThread({{ $thread->id }}, '{{ addslashes($thread->job->company->name) }}')">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-gray-900">{{ $thread->job->company->name }}</span>
                    <span class="text-xs text-gray-500">{{ $thread->updated_at->format('Y/m/d') }}</span>
                </div>
                <div class="text-sm text-gray-700 mt-1 truncate">
                    {{ optional($thread->messages->last())->message ?? '未送信' }}
                </div>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- チャット欄 --}}
    <div class="flex-1 bg-white rounded-lg shadow-md flex flex-col"
        style="min-width:450px; max-width:900px; height:600px;">
        @if($selectedThread)
        <x-chat.box :headerTitle="isset($selectedThread) ? $selectedThread->job->title : null"
            :headerSub="isset($selectedThread) ? $selectedThread->job->company->name : null"
            :mySenderType="0" />

        @else
        <div class="flex-1 flex items-center justify-center text-gray-500">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0…" />
                </svg>
                <div class="text-xl font-bold">メッセージがあります</div>
                <div class="mt-2">メッセージを選択してください</div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/chat.js') }}"></script>
@endpush