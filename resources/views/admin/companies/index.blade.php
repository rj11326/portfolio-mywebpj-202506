@extends('layouts.admin')

@section('title', '企業一覧')

@section('content')
<h1 class="text-2xl font-bold mb-6">企業一覧</h1>
@if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 rounded px-4 py-3 text-green-900">
        {{ session('success') }}
    </div>
@endif

<form method="GET" class="mb-4 flex items-center gap-2">
    <label class="inline-flex items-center">
        <input type="checkbox" name="show_trashed" value="1" onchange="this.form.submit()" {{ $showTrashed ? 'checked' : '' }}>
        <span class="ml-2 text-sm text-gray-700">削除済みの企業を表示</span>
    </label>
</form>

<table class="min-w-full bg-white border rounded shadow text-sm">
    <thead>
        <tr class="bg-gray-100">
            <th class="py-2 px-3">ID</th>
            <th class="py-2 px-3">企業名</th>
            <th class="py-2 px-3">メール</th>
            <th class="py-2 px-3">状態</th>
            <th class="py-2 px-3">操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($companies as $company)
        <tr>
            <td class="border-t px-3 py-2">{{ $company->id }}</td>
            <td class="border-t px-3 py-2">{{ $company->name }}</td>
            <td class="border-t px-3 py-2">{{ $company->email }}</td>
            <td class="border-t px-3 py-2">
                @if($company->deleted_at)
                    <span class="text-red-600 font-bold">削除済み</span>
                @else
                    <span class="text-green-600">有効</span>
                @endif
            </td>
            <td class="border-t px-3 py-2">
                @if($company->deleted_at)
                    <form method="POST" action="{{ route('admin.companies.restore', $company->id) }}">
                        @csrf
                        <button class="text-blue-600 hover:underline">復元</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" onsubmit="return confirm('本当に削除しますか？');">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">削除</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-4">
    {{ $companies->appends(['show_trashed' => $showTrashed ? 1 : 0])->links() }}
</div>
@endsection