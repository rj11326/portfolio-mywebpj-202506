@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 space-y-10">
    {{-- 個人情報セクション --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            @if(session('success'))
            <div class="mb-4 text-green-600">{{ session('success') }}</div>
            @endif
            <h2 class="text-lg font-semibold">個人情報</h2>
            <a href="{{ route('profile.edit', $user->id) }}" class="text-sm text-blue-500 hover:underline">編集</a>
        </div>
        <div class="bg-white border rounded-xl shadow p-5">
            <p><strong>氏名：</strong> {{ $user->name }}</p>
            <p><strong>メール：</strong> {{ $user->email }}</p>
            <p><strong>電話番号：</strong> {{ $user->phone ?? '未登録' }}</p>
            <p><strong>住所：</strong> {{ $user->address ?? '未登録' }}</p>
        </div>
    </section>

    {{-- 職歴セクション --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">職歴</h2>
            <a href="{{ route('workhistories.create') }}" class="text-xl text-gray-500 hover:text-red-500">＋</a>
        </div>

        @foreach ($user->workHistories as $work)
        <div>
            {{-- 編集・削除アイコン --}}
            <div class="bg-white border rounded-xl shadow p-5 mb-4 relative">
                <div class="absolute top-3 right-3 flex space-x-2 text-gray-400">
                    <a href="{{ route('workhistories.edit', $work->id) }}" class="hover:text-red-500">
                        <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    <form action="{{ route('workhistories.destroy', $work->id) }}" method="POST"
                        onsubmit="return confirm('削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button class="hover:text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                        </button>
                    </form>
                </div>

                {{-- 見出し: 職種・役職 --}}
                <div class="mb-2">
                    <span class="font-bold text-lg">{{ $work->job_title ?? '-' }}</span>
                    @if($work->position)
                    <span
                        class="inline-block ml-2 text-xs bg-gray-200 rounded-full px-3 py-1">{{ $work->position }}</span>
                    @endif
                </div>

                {{-- 会社名・勤務地 --}}
                <div class="text-sm text-gray-600 mb-1 flex items-center gap-2">
                    <span>{{ $work->company_name }}</span>
                    @if($work->location)
                    <span class="text-xs bg-gray-100 rounded px-2 py-0.5">{{ $work->location }}</span>
                    @endif
                </div>

                {{-- 期間 --}}
                <div class="text-xs text-gray-500 mb-2">
                    {{ $work->start_date }} ～ {{ $work->end_date ?? '現在' }}
                </div>

                {{-- 詳細（説明） --}}
                @if ($work->description)
                <div class="text-sm text-gray-800 whitespace-pre-line mt-3">
                    {{ $work->description }}
                </div>
                @endif
            </div>
            @endforeach
    </section>

    {{-- 学歴セクション --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">学歴</h2>
            <a href="{{ route('educations.create') }}" class="text-xl text-gray-500 hover:text-red-500">＋</a>
        </div>

        @foreach ($user->educations as $edu)
        <div>
            {{-- 編集・削除アイコン --}}
            <div class="bg-white border rounded-xl shadow p-5 mb-4 relative">
                <div class="absolute top-3 right-3 flex space-x-2 text-gray-400">
                    <a href="{{ route('educations.edit', $edu->id) }}" class="hover:text-red-500">
                        <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    <form action="{{ route('educations.destroy', $edu->id) }}" method="POST"
                        onsubmit="return confirm('削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button class="hover:text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                        </button>
                    </form>
                </div>

                <p class="font-semibold text-lg">{{ $edu->school_name }}</p>
                <div class="text-sm text-gray-600 mb-1">{{ $edu->faculty }} - {{ $edu->degree }}</div>
                <div class="text-sm text-gray-500 mb-2">{{ $edu->start_date }} ～ {{ $edu->end_date ?? '現在' }}</div>
            </div>
            @endforeach
    </section>

    {{-- 資格セクション --}}
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">資格</h2>
            <a href="{{ route('licenses.create') }}" class="text-xl text-gray-500 hover:text-red-500">＋</a>
        </div>

        @foreach ($user->licenses as $license)
        <div>
            <div class="bg-white border rounded-xl shadow p-5 mb-4 relative">
                <div class="absolute top-3 right-3 flex space-x-2 text-gray-400">
                    <a href="{{ route('licenses.edit', $license->id) }}" class="hover:text-red-500">
                        <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                    <form action="{{ route('licenses.destroy', $license->id) }}" method="POST"
                        onsubmit="return confirm('削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button class="hover:text-red-500">
                            <svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>

                        </button>
                    </form>
                </div>

                <p class="font-semibold text-lg">{{ $license->name }}</p>
                <div class="text-sm text-gray-500">取得日：{{ $license->acquired_date }}</div>
            </div>
            @endforeach
    </section>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/sanctum/csrf-cookie', {
            credentials: 'include'
        })
        .then(() => {
            return fetch('/api/user', {
                credentials: 'include'
            });
        })
});
</script>