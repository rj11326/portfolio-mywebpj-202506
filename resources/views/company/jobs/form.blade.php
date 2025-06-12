@php
$isEdit = isset($job) && empty($isCopy);
$employmentTypes = config('const.employment_types');
@endphp

<form method="POST" action="{{ $isEdit ? route('company.jobs.update', $job) : route('company.jobs.store') }}"
    class="space-y-6 bg-white p-6 rounded shadow">
    @csrf
    @if($isEdit)
    @method('PUT')
    @endif

    <div>
        <label class="block font-semibold mb-1">タイトル</label>
        <input type="text" name="title" class="w-full border rounded px-3 py-2" required
            value="{{ old('title', $job->title ?? '') }}" placeholder="求人タイトルを入力">
        @error('title') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">カテゴリ</label>
        <select name="job_category_id" class="w-full border rounded px-3 py-2" required>
            <option value="">-- 選択してください --</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" @if(old('job_category_id', $job->job_category_id ?? '') ==
                $category->id) selected @endif>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
        @error('job_category_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div x-data='tagSelector(@json(old("tags", $selectedTags ?? [])), @json($allTags ?? []))' class="mb-6">
        <label class="block font-semibold mb-2">タグ</label>
        <div class="flex flex-wrap gap-2">
            <template x-for="tag in selectedTags" :key="tag . id">
                <span class="bg-blue-100 text-blue-700 rounded-full px-3 py-1 text-xs flex items-center">
                    <span x-text="tag.label"></span>
                    <button type="button" class="ml-1 text-blue-400" @click="removeTag(tag.id)">
                        &times;
                    </button>
                    <input type="hidden" name="tags[]" :value="tag . id">
                </span>
            </template>
            <button type="button" class="bg-blue-500 text-white px-2 py-1 rounded" @click="openTagModal = true">
                タグを追加
            </button>
        </div>

        <!-- タグ選択モーダル -->
        <div x-show="openTagModal" style="display: none;"
            class="fixed inset-0 z-50 bg-black bg-opacity-40 flex items-center justify-center" x-transition>
            <div class="bg-white w-full max-w-md rounded-xl shadow p-6">
                <div class="mb-2 flex justify-between items-center">
                    <h2 class="font-bold text-lg">タグを選択</h2>
                    <button type="button" @click="openTagModal = false" class="text-gray-500 text-xl">&times;</button>
                </div>
                <input type="text" x-model="keyword" placeholder="タグ名で検索" class="border px-3 py-1 w-full mb-4 rounded">
                <div class="max-h-60 overflow-y-auto">
                    <template x-for="tag in filteredTags()" :key="tag . id">
                        <div class="flex items-center justify-between px-2 py-1 hover:bg-gray-100 rounded">
                            <span x-text="tag.label"></span>
                            <button type="button" class="bg-blue-200 px-2 py-1 rounded text-sm" @click="addTag(tag)"
                                x-show="!selectedTags.some(t => t.id === tag.id)">追加</button>
                            <span class="text-gray-400 text-xs"
                                x-show="selectedTags.some(t => t.id === tag.id)">追加済み</span>
                        </div>
                    </template>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 bg-gray-300 rounded"
                        @click="openTagModal = false">閉じる</button>
                </div>
            </div>
        </div>
    </div>

    <div>
        <label class="block font-semibold mb-1">勤務地(県)</label>
        <select name="location_id" class="w-full border rounded px-3 py-2">
            <option value="">-- 選択してください --</option>
            @foreach($locations as $location)
            <option value="{{ $location->id }}" @if(old('location_id', $job->location_id ?? '') == $location->id)
                selected @endif>
                {{ $location->name }}
            </option>
            @endforeach
        </select>
        @error('location_id') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">勤務地(詳細)</label>
        <input type="text" name="location" class="w-full border rounded px-3 py-2" placeholder="（例）東京都新宿区" required
            value="{{ old('location', $job->location ?? '') }}">
        @error('location') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">給与(年収)</label>
        <div class="flex items-center gap-2">
            <input type="number" name="salary_min" step="50" class="w-full border rounded px-3 py-2" required
                placeholder="最低年収（例）300" value="{{ old('salary_min', $job->salary_min ?? '') }}">
            <span class="mx-1 text-gray-500 text-lg font-semibold">〜</span>
            <input type="number" name="salary_max" step="50" class="w-full border rounded px-3 py-2" required
                placeholder="最高年収（例）500" value="{{ old('salary_max', $job->salary_max ?? '') }}">
        </div>
        @error('salary_min') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
        @error('salary_max') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">雇用形態</label>
        <select name="employment_type" class="w-full border rounded px-3 py-2" required>
            <option value="">-- 選択してください --</option>
            @foreach($employmentTypes as $key => $label)
            <option value="{{ $key }}" @if(old('employment_type', $job->employment_type ?? '') == $key) selected @endif>
                {{ $label }}
            </option>
            @endforeach
        </select>
        @error('employment_type') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">仕事内容</label>
        <textarea name="description" rows="5" class="w-full border rounded px-3 py-2" placeholder="具体的な業務内容を詳しく記載してください"
            required>{{ old('description', $job->description ?? '') }}</textarea>
        @error('description') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">必須条件</label>
        <textarea name="requirements" rows="3" class="w-full border rounded px-3 py-2"
            placeholder="（例）JavaScriptの実務経験2年以上">{{ old('requirements', $job->requirements ?? '') }}</textarea>
        @error('requirements') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">歓迎スキル</label>
        <textarea name="welcome_skills" rows="3" class="w-full border rounded px-3 py-2"
            placeholder="（例）TypeScriptの経験があれば歓迎します。">{{ old('welcome_skills', $job->welcome_skills ?? '') }}</textarea>
        @error('welcome_skills') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">年齢・学歴・資格</label>
        <textarea name="required_qualifications" rows="2" class="w-full border rounded px-3 py-2"
            placeholder="（例）年齢不問／学歴不問／情報処理技術者資格歓迎">{{ old('required_qualifications', $job->required_qualifications ?? '') }}</textarea>
        @error('required_qualifications') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">使用技術・ツール</label>
        <textarea name="tools" rows="2" class="w-full border rounded px-3 py-2"
            placeholder="（例）Vue.js, React, Git">{{ old('tools', $job->tools ?? '') }}</textarea>
        @error('tools') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">選考フロー</label>
        <textarea name="selection_flow" rows="2" class="w-full border rounded px-3 py-2"
            placeholder="（例）書類選考 → 一次面接（WEB）→ 最終面接 → 内定">{{ old('selection_flow', $job->selection_flow ?? '') }}</textarea>
        @error('selection_flow') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">提出書類</label>
        <input type="text" name="required_documents" class="w-full border rounded px-3 py-2" placeholder="（例）履歴書、職務経歴書"
            value="{{ old('required_documents', $job->required_documents ?? '') }}">
        @error('required_documents') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">面接地／方法</label>
        <input type="text" name="interview_place" class="w-full border rounded px-3 py-2"
            placeholder="（例）東京都新宿区／オンライン面接可" value="{{ old('interview_place', $job->interview_place ?? '') }}">
        @error('interview_place') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">福利厚生</label>
        <textarea name="benefits" rows="3" class="w-full border rounded px-3 py-2"
            placeholder="（例）リモート可・住宅手当あり・交通費支給。">{{ old('benefits', $job->benefits ?? '') }}</textarea>
        @error('benefits') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block font-semibold mb-1">勤務時間</label>
        <input type="text" name="work_time" class="w-full border rounded px-3 py-2"
            placeholder="（例）9:00〜18:00、フレックスタイム制あり" value="{{ old('work_time', $job->work_time ?? '') }}">
        @error('work_time') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block font-semibold mb-1">休日・休暇</label>
        <input type="text" name="holiday" class="w-full border rounded px-3 py-2" placeholder="（例）完全週休2日制、年末年始休暇、夏季休暇あり"
            value="{{ old('holiday', $job->holiday ?? '') }}">
        @error('holiday') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block font-semibold mb-1">募集人数</label>
        <input type="number" name="number_of_positions" class="w-full border rounded px-3 py-2"
            value="{{ old('number_of_positions', $job->number_of_positions ?? '') }}">
        @error('number_of_positions') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block font-semibold mb-1">応募締切日</label>
        <input type="date" name="application_deadline" x-model="form.application_deadline"
            value="{{ old('application_deadline', isset($job) && $job->application_deadline ? \Carbon\Carbon::parse($job->application_deadline)->format('Y-m-d') : '') }}"
            class="w-full border rounded px-3 py-2">
        @error('application_deadline') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-4 items-center">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
            {{ $isEdit ? '更新する' : '登録する' }}
        </button>
        <button type="submit" name="preview" value="1" formaction="{{ route('company.jobs.preview') }}"
            onclick="this.form.querySelector('input[name=_method]')?.remove();" formtarget="_blank"
            class="bg-gray-500 text-white px-4 py-2 rounded">
            プレビュー
        </button>
    </div>
</form>


<script>
function tagSelector(initialSelectedTagIds = [], allTags = []) {
    return {
        openTagModal: false,
        keyword: '',
        allTags: allTags,
        selectedTags: [],

        init() {
            this.selectedTags = this.allTags.filter(tag => initialSelectedTagIds.includes(tag.id));
        },
        addTag(tag) {
            if (!this.selectedTags.some(t => t.id === tag.id)) {
                this.selectedTags.push(tag);
            }
        },
        removeTag(tagId) {
            this.selectedTags = this.selectedTags.filter(t => t.id !== tagId);
        },
        filteredTags() {
            if (!this.keyword) return this.allTags;
            return this.allTags.filter(tag => tag.label.includes(this.keyword));
        }
    }
}
</script>