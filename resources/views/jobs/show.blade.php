@extends('layouts.app')

@section('title', $job->title . ' - 求人詳細')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white">

    <!-- 左カラム -->
    <div class="lg:col-span-8 space-y-10">
        <!-- 基本情報 -->
        <div class="flex items-center justify-between gap-4" x-data="jobDetail({{ $job->id }})" x-init="init()">
            <h1 class="text-3xl font-bold leading-snug">{{ $job->title }}</h1>
            @auth
            <button type="button" @click="toggleSave(jobId)" :title="isSaved(jobId) ? '保存解除' : '保存する'"
                class="text-2xl transition" aria-label="お気に入り">
                <svg class="w-6 h-6" :fill="isSaved(jobId) ? 'black' : 'white'" stroke="black" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-5-7 5V5z" />
                </svg>
            </button>
            @endauth
        </div>
        <div class="text-gray-600 text-lg mb-3">
            {{ $job->company->name ?? '非公開' }}
            @if ($job->location) - {{ $job->location }} @endif
        </div>

        <!-- 雇用形態・年収など -->
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <span class="inline-block bg-gray-50 rounded px-3 py-1 text-sm">
                {{ config('const.employment_types')[$job->employment_type] }}
            </span>
            <span class="inline-block bg-gray-50 rounded px-3 py-1 text-sm">
                年収{{ number_format($job->salary_min) }}万～{{ number_format($job->salary_max) }}万
            </span>
            @if ($job->work_time)
            <span class="inline-block bg-gray-50 rounded px-3 py-1 text-sm">
                勤務時間: {{ $job->work_time }}
            </span>
            @endif
            @if ($job->holiday)
            <span class="inline-block bg-gray-50 rounded px-3 py-1 text-sm">
                休日: {{ $job->holiday }}
            </span>
            @endif
            @if ($job->number_of_positions)
            <span class="text-sm text-gray-700">
                募集人数：{{ $job->number_of_positions }}名
            </span>
            @endif
            @if ($job->application_deadline)
            <span class="text-sm text-gray-700">
                締切：{{ \Carbon\Carbon::parse($job->application_deadline)->format('Y年n月j日') }}
            </span>
            @endif
        </div>

        <!-- タグ -->
        <div class="mb-2">
            <strong class="text-gray-700 mr-2">タグ:</strong>
            @forelse ($job->tags as $tag)
            <span
                class="inline-block bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs mr-1 mb-1">{{ $tag->label }}</span>
            @empty
            <span class="text-gray-400">なし</span>
            @endforelse
        </div>

        <!-- 画像 -->
        @php
        $imageUrls = $job->company
        ? $job->company->images()->orderBy('order')->get()
        ->map(fn($i) => str_replace('\\', '/', Storage::disk('public')->url($i->file_path)))
        ->values()
        : collect();
        @endphp

        @if ($imageUrls->isNotEmpty())
        <div class="my-6">
            @if ($imageUrls->count() > 1)
            <div x-data='{
                idx: 0, 
                imgs: @json($imageUrls)
            }' class="relative w-full max-w-xl mx-auto">
                <div class="relative overflow-hidden rounded-xl shadow-md w-full aspect-video"
                    style="min-height:200px;">
                    <template x-for="(img, i) in imgs" :key="i">
                        <img :src="img"
                            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-300" :class="{
                            'opacity-0 pointer-events-none': idx !== i,
                            'opacity-100': idx === i
                        }" alt="企業イメージ">
                    </template>
                </div>
                <!-- 左右ボタン -->
                <button @click="idx = idx === 0 ? imgs.length-1 : idx-1"
                    class="absolute top-1/2 left-2 -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="idx = idx === imgs.length-1 ? 0 : idx+1"
                    class="absolute top-1/2 right-2 -translate-y-1/2 bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-opacity-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <!-- インジケータ -->
                <div class="flex justify-center mt-3 gap-2">
                    <template x-for="i in imgs.length">
                        <span :class="idx === (i-1) ? 'bg-red-500' : 'bg-gray-300'"
                            class="inline-block w-2 h-2 rounded-full"></span>
                    </template>
                </div>
            </div>
            @elseif ($imageUrls->count() === 1)
            <img src="{{ $imageUrls->first() }}" alt="企業イメージ"
                class="rounded-xl shadow-md my-6 w-full max-w-xl mx-auto aspect-video object-cover" />
            @else
            <img src="{{ asset('images/sample.jpg') }}" alt="企業イメージ"
                class="rounded-xl shadow-md my-6 w-full max-w-xl mx-auto aspect-video object-cover" />
            @endif
        </div>
        @endif

        <!-- 仕事内容 -->
        <section>
            <h2 class="text-xl font-bold text-red-600 mb-2">仕事内容</h2>
            <div class="text-gray-800 leading-relaxed whitespace-pre-line mb-2">
                {{ $job->description }}
            </div>
            @if($job->requirements)
            <p class="mb-1"><span class="font-semibold">求める人物像・応募条件：</span>{{ $job->requirements }}</p>
            @endif
            @if($job->welcome_skills)
            <p class="mb-1"><span class="font-semibold">歓迎スキル・経験：</span>{{ $job->welcome_skills }}</p>
            @endif
            @if($job->required_qualifications)
            <p class="mb-1"><span class="font-semibold">年齢・学歴・資格：</span>{{ $job->required_qualifications }}</p>
            @endif
            @if($job->tools)
            <p class="mb-1"><span class="font-semibold">使用技術・ツール：</span>{{ $job->tools }}</p>
            @endif
        </section>

        <!-- 待遇・福利厚生 -->
        @if($job->benefits)
        <section>
            <h2 class="text-xl font-bold text-red-600 mb-2">待遇・福利厚生</h2>
            <div class="text-gray-800 leading-relaxed whitespace-pre-line">
                {{ $job->benefits }}
            </div>
        </section>
        @endif

        <!-- 選考情報 -->
        <section>
            <h2 class="text-xl font-bold text-red-600 mb-2">選考情報</h2>
            @if($job->selection_flow)
            <p><span class="font-semibold">選考フロー：</span>{{ $job->selection_flow }}</p>
            @endif
            @if($job->required_documents)
            <p><span class="font-semibold">提出書類：</span>{{ $job->required_documents }}</p>
            @endif
            @if($job->interview_place)
            <p><span class="font-semibold">面接地／方法：</span>{{ $job->interview_place }}</p>
            @endif
        </section>

        <!-- 会社情報 -->
        <section>
            <h2 class="text-xl font-bold text-red-600 mb-2">会社情報</h2>
            <p class="font-semibold mb-1">{{ $job->company->name ?? '非公開' }}</p>
            <ul class="text-gray-800 space-y-1">
                @if ($job->company)
                <li>設立：{{ \Carbon\Carbon::parse($job->company->founded_at)->format('Y年n月') }}</li>
                <li>資本金：{{ number_format($job->company->capital) }}千円</li>
                <li>事業内容：{{ $job->company->business ?? '---' }}</li>
                <li>
                    Webサイト:
                    @if($job->company->website)
                    <a href="{{ $job->company->website }}" target="_blank"
                        class="text-blue-500 hover:underline">{{ $job->company->website }}</a>
                    @else
                    なし
                    @endif
                </li>
                @endif
            </ul>
        </section>

        <!-- 6. 補足・PR -->
        <section>
            <h2 class="text-xl font-bold text-red-600 mb-2">補足・PR</h2>
            @if ($job->company && $job->company->pr)
            <div class="text-gray-800">{{ $job->company->pr }}</div>
            @else
            <div class="text-gray-500">担当者コメントやよくある質問、アピールポイント等がここに表示されます。</div>
            @endif
        </section>

        <!--  応募・お気に入りボタン -->
        <div class="flex items-center gap-4 mt-8">

            @if (empty($isPreview))
            @auth
            <a href="{{ route('applications.create', $job->id) }}"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-full text-center mt-2">
                応募フォームへ進む
            </a>
            @else
            <a href="{{ route('login', ['redirect' => route('applications.create', $job->id)]) }}"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-full text-center">ログインして応募</a>
            @endauth
            @else
            <!--  プレビュー表示時 -->
            <div class="flex-1">
                <button type="button" class="w-full bg-gray-300 text-gray-500 py-3 rounded-full cursor-not-allowed"
                    disabled>
                    プレビュー中のため応募できません
                </button>
            </div>
            @endif
        </div>

    </div>

    <!-- 右カラム -->
    <aside class="lg:col-span-4 space-y-6 py-2">
        <!-- 企業情報カード -->
        <div class="bg-white shadow rounded-xl p-6 border">
            <h2 class="font-bold text-lg mb-3">{{ $job->company->name ?? '非公開' }}</h2>
            <p class="text-sm text-gray-700">{{ $job->company->description ?? '企業の紹介文が入ります。' }}</p>

            <div class="mt-6 text-sm text-gray-600 space-y-2">
                <p><strong>設立：</strong> {{ \Carbon\Carbon::parse($job->company->founded_at)->format('Y年n月') }}</p>
                <p><strong>資本金：</strong> {{ number_format($job->company->capital) }}千円</p>
                <p><strong>従業員数：</strong> {{ $job->company->employee_count }}人</p>
            </div>
        </div>

        <!-- 関連求人リスト -->
        @if ($companyJobs->count() > 0)
        <div class="bg-white shadow rounded-xl p-6 border">
            <h2 class="font-bold text-lg mb-4">募集中の求人</h2>
            <ul class="space-y-3 text-sm text-gray-800">
                @foreach ($companyJobs as $relatedJob)
                <li class="flex items-start justify-between">
                    <div>
                        <p class="font-medium">{{ $relatedJob->title }}</p>
                        <p class="text-xs text-gray-500">{{ $relatedJob->location }}</p>
                    </div>
                    <p class="text-sm text-gray-600">
                        {{ number_format($relatedJob->salary_min) }}万～{{ number_format($relatedJob->salary_max) }}万
                    </p>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </aside>

</div>
@endsection

<script>
function jobDetail(jobId) {
    return {
        jobId: jobId,
        savedJobIds: [],
        init() {
            fetch('/api/saved-jobs', {
                    credentials: 'include',
                })
                .then(res => res.ok ? res.json() : {
                    saved_job_ids: []
                })
                .then(data => this.savedJobIds = data.saved_job_ids || []);
        },
        isSaved(id) {
            return this.savedJobIds.includes(id);
        },
        toggleSave(id) {
            fetch(`/api/jobs/${id}/save`, {
                    method: 'POST',
                    credentials: 'include',
                    headers: {
                        'Accept': 'application/json',
                        'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.result === 'saved') {
                        this.savedJobIds.push(id);
                    } else if (data.result === 'removed') {
                        this.savedJobIds = this.savedJobIds.filter(jid => jid !== id);
                    }
                });
        }
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}
</script>