@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <!-- 検索バー -->
    <form method="GET" action="{{ route('jobs.index') }}">
        <div x-data="jobSearchBar()" x-init="fetchCategories(); fetchLocationAreas()"
            class="max-w-5xl mx-auto my-6 mb-8 rounded-full border border-gray-200 bg-white shadow flex items-center px-6 py-2 gap-4">
            <!-- キーワード -->
            <input type="text" name="q" x-model="keyword"
                class="flex-1 border-none focus:ring-0 bg-transparent px-4 text-gray-700 text-base rounded-full placeholder-gray-400 outline-none text-left" placeholder="キーワード" />
            <!-- 職種 -->
            <div class="hidden md:flex flex-[2] items-center border-l border-gray-200 h-8 pl-6">
                <button type="button" class="w-full text-gray-700 text-left focus:outline-none"
                    @click="openJobCategoryModal = true">
                    <template x-if="selectedJobCategoryNames.length === 0">
                        <span>職種</span>
                    </template>
                    <template x-if="selectedJobCategoryNames.length > 0">
                        <span class="block overflow-hidden text-ellipsis whitespace-nowrap max-w-[250px]"
                            x-text="selectedJobCategoryNames.join(', ')">
                        </span>
                    </template>
                </button>
                <input type="hidden" name="job_categories" :value="selectedJobCategoryIds.join(',')">
            </div>
            <!-- 勤務地 -->
            <div class="hidden md:flex flex-[2] items-center border-l border-gray-200 h-8 pl-6">
                <button type="button" class="w-full text-gray-700 text-left focus:outline-none"
                    @click="openLocationModal = true">
                    <template x-if="selectedLocationNames.length === 0">
                        <span>勤務地</span>
                    </template>
                    <template x-if="selectedLocationNames.length > 0">
                        <span class="block overflow-hidden text-ellipsis whitespace-nowrap max-w-[250px]"
                            x-text="selectedLocationNames.join(', ')">
                        </span>
                    </template>
                </button>
                <input type="hidden" name="locations" :value="selectedLocationIds.join(',')">
            </div>
            <!-- 年収 -->
            <div class="hidden md:flex flex-[2] items-center border-l border-gray-200 h-8 pl-6">
                <template x-if="jobCategories.length > 0 && locationAreas.length > 0">
                    <div x-data="salaryDropdown()" x-cloak class="relative w-full">
                        <button type="button"
                            class="w-full bg-transparent border-none text-gray-700 text-left pr-8 focus:outline-none h-8 flex items-center"
                            @click="open = !open" :class="{'border border-gray-300 bg-white shadow': open}">
                            <span x-text="selectedSalaryLabel"></span>
                            <svg class="w-4 h-4 ml-auto text-gray-400 absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <!-- ドロップダウン -->
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute left-0 right-0 mt-2 bg-white shadow-lg rounded-lg py-2 z-50 max-h-80 overflow-y-auto border border-gray-200"
                            style="min-width: 120px;">
                            <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-100"
                                @click="select(null)">
                                指定なし
                            </button>
                            <template x-for="salary in salaryOptions" :key="salary">
                                <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-100"
                                    @click="select(salary)">
                                    <span x-text="salary + '万円以上'"></span>
                                </button>
                            </template>
                        </div>
                        <input type="hidden" name="salary" :value="selectedSalary">
                    </div>
                </template>
            </div>


            <!-- 検索ボタン -->
            <button type="submit" class="ml-4 bg-red-500 hover:bg-red-600 text-white rounded-full p-2">
                <svg xmlns="https://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <circle cx="11" cy="11" r="7" stroke-width="2" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2" />
                </svg>
            </button>
            @include('partials.job-category-modal')
            @include('partials.location-modal')
        </div>
    </form>

    <!-- 注目の求人 -->
    <h2 class="text-xl font-semibold mb-3">注目の求人</h2>
    <div class="flex mb-8 gap-6 overflow-x-auto pb-2 hide-scrollbar">
        @forelse ($featuredJobs as $job)
        <a href="{{ route('jobs.show', ['job' => $job->id]) }}"
            class="bg-white rounded-2xl shadow px-6 py-5 min-w-[280px] max-w-xs block hover:bg-gray-50 transition relative">

            {{-- ★ここにお気に入りボタンを後で配置する用スペースを確保（今は何も表示しない） --}}
            <div class="absolute top-3 right-4">
                {{-- お気に入りボタン設置予定 --}}
            </div>

            <div class="font-bold text-lg mb-1">{{ $job->title }}</div>
            <div class="text-gray-800 text-base">{{ $job->company_name }}</div>
            <div class="text-gray-500 text-sm mb-2">
                {{ $job->location }}
            </div>

            <div class="flex flex-wrap gap-2 mb-2">
                @foreach($job->tags->take(3) as $tag)
                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                    {{ $tag->label }}
                </span>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-2 mb-2 text-xs">
                @if($job->salary)
                <span class="bg-gray-50 text-gray-800 px-2 py-1 rounded">
                    年収{{ number_format($job->salary / 10000) }}万円
                </span>
                @endif
                @if($job->employment_type)
                <span class="bg-gray-50 text-gray-800 px-2 py-1 rounded">
                    {{ config('const.employment_types')[$job->employment_type] ?? 'その他' }}
                </span>
                @endif
            </div>

            <div class="text-gray-600 text-sm line-clamp-2">
                {{ Str::limit($job->description, 45) }}
            </div>
        </a>
        @empty
        <div class="text-gray-400">注目の求人はありません</div>
        @endforelse
    </div>

    <!-- カテゴリー -->
    <h2 class="text-xl font-semibold mb-3">カテゴリー</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 mt-4">
        @foreach ($mainCategories as $cat)
        <a href="{{ route('jobs.index', ['job_categories' => $cat->children->pluck('id')->implode(',')]) }}"
            class="bg-white rounded-2xl shadow flex flex-col items-center p-6 hover:shadow-lg transition group">
            <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-2 border-gray-50  transition">
                <img src="/images/category-{{ $cat->icon }}.jpg" alt="{{ $cat->name }}"
                    class="w-full h-full object-cover">
            </div>
            <div class="font-semibold text-center text-gray-800 ">
                {{ $cat->name }}
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection
@push('scripts')
<script src="{{ mix('js/user-home.js') }}"></script>
@endpush