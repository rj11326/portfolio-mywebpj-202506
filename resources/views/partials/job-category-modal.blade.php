<!-- 職種モーダル -->
<div x-show="openJobCategoryModal" class="fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-center items-center"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0" style="display: none;">
    <div class="bg-white w-full max-w-2xl rounded-xl shadow p-6 flex flex-col" style="max-height: 80vh;">
        <h2 class="font-bold text-lg mb-4">職種を選択</h2>
        <div class="overflow-y-auto flex-1" style="max-height: 60vh;">
            <template x-for="(parent, idx) in jobCategories" :key="parent.id">
                <div class="mb-2 border-b pb-2">
                    <!-- アコーディオンヘッダー -->
                    <div class="relative">
                        <button type="button"
                            class="w-full flex justify-between items-center font-semibold py-2 text-left pr-8 relative"
                            :data-test="'job-category-parent-' + parent.id"
                            @click="parent.open = !parent.open" :aria-expanded="parent.open">
                            <span x-text="parent.name"></span>
                            <svg :class="{'rotate-180': parent.open}"
                                class="w-4 h-4 transition-transform absolute right-2 top-1/2 -translate-y-1/2 z-10"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <!-- 子カテゴリ -->
                    <div x-show="parent.open" x-transition:enter="transition-all duration-200 ease-in-out"
                        x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                        x-transition:leave="transition-all duration-200 ease-in-out"
                        x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-6 pt-2 flex flex-wrap gap-2" style="overflow: hidden;">
                        <template x-for="child in parent.children" :key="child.id">
                            <label
                                class="relative inline-flex items-center border rounded-full px-3 py-1 cursor-pointer select-none mr-2 mb-2 transition" :data-test="'job-category-child-' + child.id"
                                :class="selectedJobCategoryIds.includes(child.id.toString()) ? 'bg-red-100 text-red-700 border-red-300' : 'bg-gray-100 text-gray-700 border-gray-200 hover:bg-red-50'">
                                <input type="checkbox" class="absolute opacity-0 w-0 h-0" :value="child.id"
                                    x-model="selectedJobCategoryIds" @click.stop>
                                <span x-text="child.name"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <div class="flex justify-end mt-6">
            <button type="button" @click="openJobCategoryModal = false" class="mr-2 text-gray-500">キャンセル</button>
            <button type="button" @click="confirmJobCategories" class="bg-red-500 text-white px-4 py-2 rounded-full" data-test="job-category-confirm-button">確定する</button>
        </div>
    </div>
</div>