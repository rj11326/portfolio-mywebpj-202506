@extends('layouts.app')

@section('title', '求人一覧')

@section('content')
<div x-data="jobFilter()" x-init="init();" @search.window="onSearch($event)"
    class="max-w-7xl mx-auto py-10 px-4 grid md:grid-cols-4 gap-8">
    <!-- サイドバーフィルター(PC) -->
    <aside class="md:col-span-1 bg-gray-100 p-6 rounded-lg shadow">
        <!-- フィルター（スマホ用トグル） -->
        <div class="md:hidden mb-4">
            <button type="button" @click="filterOpen = !filterOpen"
                class="w-full flex items-center justify-between px-4 py-2 bg-white border rounded shadow font-semibold">
                <span>フィルター</span>
                <svg :class="filterOpen ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>
        <h2 class="text-lg font-semibold mb-4 hidden md:block">フィルター</h2>
        <div x-show="filterOpen" class="transition-all duration-200"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4">
            <div class="mb-4">
                <label class="block font-medium mb-2">キーワード検索</label>
                <input type="text" x-model="keyword" @keydown.enter="fetchJobs" @blur="fetchJobs" placeholder="職種・会社名など"
                    class="w-full rounded-full border px-4 py-2 text-sm shadow-sm focus:ring focus:ring-red-300">
            </div>

            <!-- タグフィルター -->
            <div class="mb-4">
                <div class="flex flex-wrap gap-2">
                    <template x-for="tag in tags" :key="tag.id">
                        <button type="button" class="px-4 py-1 rounded-full border border-gray-300 text-sm"
                            :data-test="'tag-' + tag.id"
                            :class="activeTags.includes(tag.id) ? 'bg-red-500 text-white border-red-500' : 'bg-white text-gray-700'"
                            @click="toggleTag(tag.id)" x-text="tag.label"></button>
                    </template>
                </div>
            </div>

            <!-- 雇用形態 -->
            <div class="mb-4">
                <label class="block font-medium mb-2">雇用形態</label>
                <div class="flex flex-col gap-1">
                    <label class="inline-flex items-center">
                        <input type="checkbox" value="1" x-model="employmentTypes" @change="fetchJobs()"
                            data-test="employment-type-1"
                            class="mr-2 rounded border-gray-300">
                        <span>正社員</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" value="2" x-model="employmentTypes" @change="fetchJobs()"
                            data-test="employment-type-2"
                            class="mr-2 rounded border-gray-300">
                        <span>契約社員</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="checkbox" value="3" x-model="employmentTypes" @change="fetchJobs()"
                            data-test="employment-type-3"
                            class="mr-2 rounded border-gray-300">
                        <span>業務委託</span>
                    </label>
                </div>
            </div>

            <!-- 職種 -->
            <div class="mb-4">
                <label class="block font-medium mb-2">職種</label>
                <button type="button" class="w-full text-left bg-white border rounded px-4 py-2"
                    data-test="job-category-button"
                    @click="openJobCategoryModal = true">
                    <template x-if="selectedJobCategoryNames.length === 0">
                        <span>選択してください</span>
                    </template>
                    <template x-if="selectedJobCategoryNames.length > 0">
                        <span class="block overflow-hidden text-ellipsis whitespace-nowrap max-w-[220px]"
                            x-text="selectedJobCategoryNames.join(', ')"></span>
                    </template>
                </button>
            </div>

            <!-- 勤務地 -->
            <div class="mb-4">
                <label class="block font-medium mb-2">勤務地</label>
                <button type="button" class="w-full text-left bg-white border rounded px-4 py-2"
                    data-test="location-button"
                    @click="openLocationModal = true">
                    <template x-if="selectedLocationNames.length === 0">
                        <span>選択してください</span>
                    </template>
                    <template x-if="selectedLocationNames.length > 0">
                        <span class="block overflow-hidden text-ellipsis whitespace-nowrap max-w-[220px]"
                            x-text="selectedLocationNames.join(', ')"></span>
                    </template>
                </button>
            </div>

            @include('partials.job-category-modal')
            @include('partials.location-modal')


            <!-- 給与 -->
            <div class="mb-4">
                <label class="block font-medium mb-2">年収（万円）</label>
                <div class="flex items-center gap-3">
                    <input type="range" min="300" max="1000" step="50" x-model="salary" @input="fetchJobs()"
                        @blur="fetchJobs()" class="w-full accent-red-500">
                    <span class="inline-block w-12 text-right" x-text="salary + '万'"></span>
                </div>
            </div>
        </div>
    </aside>

    <!-- ジョブカード -->
    <section class="md:col-span-3">
        <!-- ソートボタン -->
        <div class="flex items-center gap-4 mb-6">
            <div class="text-sm font-semibold">並び替え:</div>
            <button type="button" class="px-3 py-1 rounded-full border transition text-sm"
                :class="sort === 'date' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-gray-700 border-gray-300'"
                @click="sort = 'date'; fetchJobs()">
                日付順
            </button>
            <button type="button" class="px-3 py-1 rounded-full border transition text-sm"
                :class="sort === 'salary' ? 'bg-red-500 text-white border-red-500' : 'bg-white text-gray-700 border-gray-300'"
                @click="sort = 'salary'; fetchJobs()">
                年収順
            </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 auto-rows-min" id="job-list">
            <template x-for="job in jobs" :key="job.id">
                <div class="relative bg-white p-6 rounded-2xl shadow border min-h-[240px] flex flex-col group">
                    <!-- 求人保存ボタン(ログイン時のみ表示) -->
                    @auth
                    <button type="button" class="absolute top-3 right-3" @click="toggleSave(job.id)"
                        :title="isSaved(job.id) ? '保存解除' : '保存する'">
                        <svg class="w-6 h-6" :fill="isSaved(job.id) ? 'currentColor' : 'none'" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-5-7 5V5z" />
                        </svg>
                    </button>
                    @endauth
                    <h3 class="text-lg font-bold text-gray-900 mb-1" x-text="job.title"></h3>
                    <div class="text-sm text-gray-600 mb-2" x-text="job.company_name + ' - ' + job.location"></div>
                    <div class="flex flex-wrap gap-2 mb-2">
                        <template x-for="tag in (job.tags || []).slice(0,3)" :key="tag">
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs" x-text="tag"></span>
                        </template>
                    </div>
                    <div class="flex flex-wrap gap-4 text-xs text-gray-500 mb-1">
                        <template x-if="job.salary_min && job.salary_max">
                            <span class="inline-block bg-gray-50 px-2 py-1 rounded">
                                年収<span x-text="job.salary_min"></span>万～<span x-text="job.salary_max"></span>万
                            </span>
                        </template>
                        <template x-if="job.employment_type">
                            <span class="inline-block bg-gray-50 px-2 py-1 rounded" x-text="job.employment_type"></span>
                        </template>
                    </div>
                    <div class="text-sm text-gray-800 mt-1 mb-3 line-clamp-2 flex-1" x-text="job.description"></div>
                    <!-- 応募ボタン(応募済みの場合はグレー) -->
                    <div class="mt-auto">
                        <template x-if="isApplied(job.id)">
                            <a :href="'/jobs/' + job.id"
                                class="block w-full text-center bg-gray-300 text-gray-500 font-semibold py-2 rounded-full">
                                応募済み
                            </a>
                        </template>
                        <template x-if="!isApplied(job.id)">
                            <a :href="'/jobs/' + job.id"
                                class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-full transition">
                                応募する
                            </a>
                        </template>
                    </div>
                </div>
            </template>
            <!-- 検索結果が空の場合 -->
            <template x-if="jobs.length === 0">
                <div class="col-span-2 text-gray-400 text-center py-10">該当する求人がありません</div>
            </template>
        </div>

        <!-- ページネーションボタン -->
        <div class="flex justify-center mt-8" x-show="lastPage > 1">
            <button class="px-3 py-1 mx-1 rounded border"
                :class="currentPage === 1 ? 'bg-gray-200 text-gray-400' : 'bg-white'"
                @click="fetchJobs(currentPage - 1)" :disabled="currentPage === 1">
                前へ
            </button>
            <template x-for="page in lastPage" :key="page">
                <button class="px-3 py-1 mx-1 rounded border"
                    :class="currentPage === page ? 'bg-red-500 text-white' : 'bg-white'" @click="fetchJobs(page)"
                    x-text="page">
                </button>
            </template>
            <button class="px-3 py-1 mx-1 rounded border"
                :class="currentPage === lastPage ? 'bg-gray-200 text-gray-400' : 'bg-white'"
                @click="fetchJobs(currentPage + 1)" :disabled="currentPage === lastPage">
                次へ
            </button>
        </div>
    </section>
</div>
@endsection
@push('scripts')
<script src="{{ mix('js/user-jobs-index.js') }}"></script>
@endpush