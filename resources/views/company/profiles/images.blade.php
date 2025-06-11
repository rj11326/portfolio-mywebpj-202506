@extends('layouts.company')

@section('title', '企業画像管理')

@section('content')

<form x-data="imageUploader()" @submit.prevent="submitForm" enctype="multipart/form-data">
    @csrf

    <div class="mb-4 text-sm text-gray-600 text-center flex flex-col items-center">
        <svg class="w-8 h-8 inline text-blue-400 mb-1" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16M10 12h4"/>
        </svg>
        <span>画像は <span class="font-semibold text-blue-600">ドラッグ＆ドロップ</span> で並び替えできます</span>
    </div>
    <div class="flex gap-4 justify-center mb-8"
         x-ref="imageList"
         @dragover.prevent
         @drop="onDrop">
        <template x-for="(image, idx) in images" :key="image.id || image.name">
            <div class="relative w-40 h-40 bg-white border rounded-lg flex items-center justify-center overflow-hidden
                        cursor-move hover:bg-blue-50 hover:shadow-lg transition"
                 draggable="true"
                 @dragstart="onDragStart(idx)"
                 @dragover.prevent="onDragOver(idx)"
                 @dragend="onDragEnd"
                 :class="draggedIndex === idx ? 'opacity-40 ring-4 ring-blue-200' : ''"
            >
                <img :src="image.url" class="object-cover w-full h-full rounded-lg">
                <!-- 削除 -->
                <button type="button" @click.stop="remove(idx)"
                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center shadow z-10">
                    &times;
                </button>
            </div>
        </template>
        <template x-if="images.length < 3">
            <div class="relative w-40 h-40 border-2 border-dashed border-gray-300 flex items-center justify-center rounded-lg cursor-pointer hover:border-blue-400 transition"
                @click="$refs.fileInput.click()">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <input type="file" x-ref="fileInput" @change="handleFileChange" class="hidden" accept="image/*" multiple>
            </div>
        </template>
    </div>
    <button type="submit"
        class="mt-2 bg-blue-600 text-white px-8 py-2 rounded hover:bg-blue-700 block mx-auto">保存</button>
</form>

<script>
function imageUploader() {
    return {
        images: @json($existingImages ?? []),
        deletedImages: [],
        draggedIndex: null,
        overIndex: null,
        handleFileChange(e) {
            let files = Array.from(e.target.files);
            files = files.slice(0, 3 - this.images.length);
            for (let file of files) {
                if (this.images.length < 3 && file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = ev => {
                        this.images.push({
                            name: file.name,
                            url: ev.target.result,
                            file: file
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }
            e.target.value = '';
        },
        remove(idx) {
            const img = this.images[idx];
            if (img.id) this.deletedImages.push(img.id);
            this.images.splice(idx, 1);
        },
        // ---- 並び替え(ドラッグ&ドロップ) ----
        onDragStart(idx) {
            this.draggedIndex = idx;
        },
        onDragOver(idx) {
            this.overIndex = idx;
            if (this.draggedIndex !== null && this.draggedIndex !== idx) {
                const moved = this.images.splice(this.draggedIndex, 1)[0];
                this.images.splice(idx, 0, moved);
                this.draggedIndex = idx;
            }
        },
        onDragEnd() {
            this.draggedIndex = null;
            this.overIndex = null;
        },
        onDrop() {
            this.draggedIndex = null;
            this.overIndex = null;
        },
        async submitForm() {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('deleted_images', JSON.stringify(this.deletedImages));
            this.images.forEach((img) => {
                if (img.id) {
                    formData.append('existing_images[]', img.id);
                } else if (img.file) {
                    formData.append('images[]', img.file);
                }
            });
            try {
                let res = await fetch("{{ route('company.images.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    },
                });
                if (res.ok) {
                    window.location.reload();
                } else {
                    let data = await res.json();
                    alert(data.message || "エラーが発生しました");
                }
            } catch (e) {
                alert("通信エラー：" + e.message);
            }
        }
    }
}
</script>
@endsection
