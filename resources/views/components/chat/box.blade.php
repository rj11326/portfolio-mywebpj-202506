@props([
    'headerTitle' => null,
    'headerSub' => null,
    'mySenderType' => 0,
])

@if (!empty($headerTitle) || !empty($headerSub))
<div class="sticky top-0 z-10 border-b bg-white px-6 py-4 flex items-center justify-between">
    <div>
        @if (!empty($headerTitle))
        <h2 class="text-lg font-semibold">{{ $headerTitle }}</h2>
        @endif
        @if (!empty($headerSub))
        <p class="text-sm text-gray-500">{{ $headerSub }}</p>
        @endif
    </div>
</div>
@endif

{{-- メッセージ一覧 --}}
<div class="flex-1 p-6 overflow-y-auto" id="messages-list">
    <template x-for="message in messages" :key="message.id">
        <div class="mb-6 flex w-full" :class="message.sender_type == mySenderType  ? 'justify-end' : 'justify-start'">
            <template x-if="message.sender_type != mySenderType">
                <div class="flex items-end">
                    <div class="max-w-lg rounded-lg px-3 py-2 bg-gray-100 text-gray-800 flex flex-col">
                        <div class="w-full whitespace-pre-wrap" x-text="message.message"></div>
                        <template x-if="message.files && message.files.length">
                            <div class="mt-2 flex flex-col gap-2 w-full">
                                <template x-for="file in message.files" :key="file.id">
                                    <a :href="file.url" target="_blank"
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-blue-50 shadow-sm border text-blue-900 font-medium transition">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-3A2.25 2.25 0 0 0 8.25 5.25V9m7.5 0v10.125c0 .621-.504 1.125-1.125 1.125h-9.75A1.125 1.125 0 0 1 3 19.125V9m15 0v.008M12 12v4.5m0 0l2.25-2.25M12 16.5l-2.25-2.25" />
                                        </svg>
                                        <span class="truncate" x-text="file.file_name"></span>
                                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                        </svg>
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="text-xs text-gray-500 ml-2 flex-shrink-0" x-text="message.created_at"></div>
                </div>
            </template>
            <template x-if="message.sender_type == mySenderType">
                <div class="flex items-end">
                    <div class="text-xs text-gray-500 mr-2 flex-shrink-0" x-text="message.created_at"></div>
                    <div class="max-w-lg rounded-lg px-3 py-2 bg-green-400 text-white flex flex-col items-end">
                        <div class="w-full whitespace-pre-wrap" x-text="message.message"></div>
                        <template x-if="message.files && message.files.length">
                            <div class="mt-2 flex flex-col gap-2 w-full">
                                <template x-for="file in message.files" :key="file.id">
                                    <a :href="file.url" target="_blank"
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white hover:bg-blue-50 shadow-sm border text-blue-900 font-medium transition">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-3A2.25 2.25 0 0 0 8.25 5.25V9m7.5 0v10.125c0 .621-.504 1.125-1.125 1.125h-9.75A1.125 1.125 0 0 1 3 19.125V9m15 0v.008M12 12v4.5m0 0l2.25-2.25M12 16.5l-2.25-2.25" />
                                        </svg>
                                        <span class="truncate" x-text="file.file_name"></span>
                                        <svg class="w-5 h-5 text-gray-400 ml-auto" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                                        </svg>
                                    </a>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </template>
</div>

{{-- 送信フォーム --}}
<form :action="''" method="POST" enctype="multipart/form-data" x-data="{ expanded: false, filesList: [], body: '' }"
    @submit.prevent="sendMessage" class="flex flex-col border-t">
    @csrf
    <div x-show="filesList.length" class="px-4 pt-2">
        <template x-for="(file, index) in filesList" :key="index">
            <div class="flex items-center justify-between bg-gray-100 px-3 py-2 rounded mb-2">
                <span class="text-sm text-gray-700 truncate" x-text="file.name"></span>
                <button type="button" @click="filesList.splice(index, 1)"
                    class="ml-2 text-gray-500 hover:text-gray-800">
                    <svg xmlns="https://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </template>
    </div>

    <div class="relative flex items-start gap-2 p-4">
        <textarea name="body" x-model="body" :class="expanded ? 'h-40' : 'h-12'"
            class="flex-1 border rounded resize-none px-3 py-2 transition-all duration-200" placeholder="メッセージを入力..."
            required></textarea>
        <button type="button" @click="expanded = !expanded"
            class="absolute top-6 right-6 p-1 bg-white rounded-full shadow hover:bg-gray-100 transition">
            <template x-if="!expanded">
                <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                </svg>
            </template>
            <template x-if="expanded">
                <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </template>
        </button>
    </div>

    <div class="flex items-center gap-2 px-4 pb-4">
        <input type="file" name="files[]" x-ref="fileInput" id="file-input" class="hidden" multiple @change="
                Array.from($event.target.files).forEach(f => {
                    if (!filesList.some(ff => ff.name === f.name && ff.size === f.size)) {
                        filesList.push(f);
                    }
                });
                $refs.fileInput.value = null;
            ">
        <button type="button" @click="$refs.fileInput.click()" class="p-2 rounded hover:bg-gray-100">
            <svg xmlns="https://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>
        </button>
        <button type="submit" class="ml-auto bg-blue-600 text-white px-4 py-2 rounded">送信する</button>
    </div>
</form>