@extends('layouts.app')

@section('title', '求人への応募')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow my-10">
    <h1 class="text-2xl font-bold mb-6">「{{ $job->title }}」への応募</h1>
    @if(session('error'))
    <div class="mb-4 text-red-600">{{ session('error') }}</div>
    @endif
    <form id="application-form" method="POST" action="{{ route('applications.store', $job->id) }}" enctype="multipart/form-data"
        class="space-y-6">
        @csrf

        <div>
            <label class="block font-semibold mb-1" for="motivation">志望動機・自己PR</label>
            <textarea name="motivation" id="motivation" rows="4" class="w-full border rounded p-2"
                maxlength="2000">{{ old('motivation') }}</textarea>
            @error('motivation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="message">応募メッセージ</label>
            <textarea name="message" id="message" rows="3" class="w-full border rounded p-2"
                maxlength="1000">{{ old('message') }}</textarea>
            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1" for="resume">職務経歴書（PDF, Word 4MBまで・複数可）</label>
            <input type="file" name="resume[]" id="resume" accept=".pdf,.doc,.docx" class="block w-full" multiple
                style="display:none;">
            <button type="button" id="resume-btn" class="bg-gray-200 px-3 py-2 rounded mb-2">ファイルを選択</button>
            <ul id="resume-list" class="mb-2"></ul>
            @error('resume') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @error('resume.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded font-semibold">
            応募を送信する
        </button>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('resume');
    const btn = document.getElementById('resume-btn');
    const list = document.getElementById('resume-list');
    const form = document.getElementById('application-form');
    let files = [];

    btn.addEventListener('click', () => input.click());

    input.addEventListener('change', (e) => {
        for (const file of e.target.files) {
            files.push(file);
        }
        renderList();
        input.value = '';
    });

    function renderList() {
        list.innerHTML = '';
        files.forEach((file, idx) => {
            const li = document.createElement('li');
            li.className = "flex items-center gap-2 mb-1";
            li.innerHTML = `
                <span>${file.name}</span>
                <button type="button" class="text-red-500 text-lg" onclick="removeFile(${idx})">&times;</button>
            `;
            list.appendChild(li);
        });
    }

    window.removeFile = function(idx) {
        files.splice(idx, 1);
        renderList();
    };

    form.addEventListener('submit', function(e) {
        if (files.length) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.delete('resume[]');
            files.forEach(file => formData.append('resume[]', file));
            fetch(this.action, {
                method: this.method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                }
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    window.location.reload();
                }
            });
        }
    });
});
</script>
@endsection