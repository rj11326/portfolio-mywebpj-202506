<!-- 勤務地モーダル -->
<div x-show="openLocationModal"
    class="fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-center items-center"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:leave="transition ease-in duration-100" x-transition:leave-end="opacity-0" style="display: none;">
    <div class="bg-white w-full max-w-xl rounded-xl shadow p-6 flex flex-col" style="max-height: 80vh;">
        <h2 class="font-bold text-lg mb-4">勤務地を選択</h2>
        <div class="overflow-y-auto flex-1" style="max-height: 60vh;">
            <template x-for="(area, idx) in locationAreas" :key="area.id">
                <div class="mb-1 border-b pb-1">
                    <!-- 親エリアのアコーディオンヘッダー -->
                    <button type="button"
                        class="w-full flex justify-between items-center py-3 text-base text-left font-medium pr-8 relative"
                        :data-test="'area-' + area.id"
                        @click="area.open = !area.open" :aria-expanded="area.open">
                        <span x-text="area.name"></span>
                        <svg :class="{'rotate-180': area.open}"
                            class="w-4 h-4 transition-transform absolute right-2 top-1/2 -translate-y-1/2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                    <!-- 子リスト -->
                    <div x-show="area.open"
                        x-transition:enter="transition-all duration-400"
                        x-transition:enter-start="opacity-0 max-h-0"
                        x-transition:enter-end="opacity-100 max-h-48"
                        x-transition:leave="transition-all duration-300"
                        x-transition:leave-start="opacity-100 max-h-48"
                        x-transition:leave-end="opacity-0 max-h-0"
                        class="pl-4 pt-2 flex flex-wrap gap-2 overflow-hidden"
                    >
                        <template x-for="pref in area.children" :key="pref.id">
                            <label class="inline-flex items-center cursor-pointer select-none px-2 py-1 rounded-full transition
                                bg-gray-50 border border-gray-200 hover:bg-red-50"
                                :data-test="'location-' + pref.id"
                                :class="selectedLocationIds.includes(pref.id.toString()) ? 'bg-red-100 text-red-700 border-red-300' : ''">
                                <input type="checkbox" class="absolute opacity-0 w-0 h-0"
                                    :value="pref.id" x-model="selectedLocationIds" @click.stop>
                                <span x-text="pref.name"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        <div class="flex justify-end mt-6 gap-2">
            <button type="button" @click="openLocationModal = false" class="text-gray-500">キャンセル</button>
            <button type="button" @click="confirmLocations" class="bg-red-500 text-white px-4 py-2 rounded-full" data-test="location-confirm-button">確定する</button>
        </div>
    </div>
</div>
