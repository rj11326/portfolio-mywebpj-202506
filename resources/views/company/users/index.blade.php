{{-- resources/views/company/users/index.blade.php --}}
@extends('layouts.company')

@section('title', '担当者一覧')

@section('content')
<div class="container py-8">

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="text-2xl font-bold mb-6">担当者一覧</h1>

    <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex justify-end mb-4">
            <a href="{{ route('company.users.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 4v16m8-8H4"/></svg>
                新規担当者追加
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-4 py-2 font-bold text-left text-gray-700">名前</th>
                        <th class="px-4 py-2 font-bold text-left text-gray-700">メール</th>
                        <th class="px-4 py-2 font-bold text-left text-gray-700">権限</th>
                        <th class="px-4 py-2 font-bold text-left text-gray-700">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <span class="inline-block px-3 py-1 rounded-full text-xs
                                {{ $user->role === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                {{ config('const.company_roles')[$user->role] }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('company.users.edit', $user->id) }}"
                                class="inline-block px-3 py-1 rounded-lg bg-yellow-50 text-yellow-800 border border-yellow-200 hover:bg-yellow-100 transition text-xs font-semibold">
                                編集
                            </a>
                            @if(auth('company')->id() !== $user->id)
                                <form action="{{ route('company.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-block px-3 py-1 rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition text-xs font-semibold"
                                        onclick="return confirm('本当に削除しますか？')">
                                        削除
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('company.users.reset_password', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-block px-3 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition text-xs font-semibold">
                                    パスワード再発行
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-8">担当者が登録されていません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
