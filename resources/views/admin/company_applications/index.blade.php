@extends('layouts.admin')

@section('title', '企業申請一覧')

@section('content')
    <h1 class="text-xl font-bold mb-4">企業申請一覧</h1>

    @if(session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <table class="min-w-full bg-white rounded-xl shadow mb-8">
        <thead>
            <tr>
                <th class="py-2 px-3">会社名</th>
                <th class="py-2 px-3">担当者</th>
                <th class="py-2 px-3">メール</th>
                <th class="py-2 px-3">状態</th>
                <th class="py-2 px-3">申請日</th>
                <th class="py-2 px-3"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
                <tr>
                    <td class="py-2 px-3">{{ $app->company_name }}</td>
                    <td class="py-2 px-3">{{ $app->contact_name }}</td>
                    <td class="py-2 px-3">{{ $app->contact_email }}</td>
                    <td class="py-2 px-3">
                        @if($app->status === 'pending')
                            <span class="text-yellow-500">審査中</span>
                        @elseif($app->status === 'approved')
                            <span class="text-green-600">承認</span>
                        @else
                            <span class="text-red-500">却下</span>
                        @endif
                    </td>
                    <td class="py-2 px-3">{{ $app->created_at->format('Y-m-d') }}</td>
                    <td class="py-2 px-3">
                        <a href="{{ route('admin.company_applications.show', $app->id) }}" class="text-blue-500 hover:underline">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $applications->links() }}
@endsection
