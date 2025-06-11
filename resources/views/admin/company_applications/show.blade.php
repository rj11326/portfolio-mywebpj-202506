@extends('layouts.admin')

@section('content')
    <h1 class="text-xl font-bold mb-4">企業申請詳細</h1>

    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="mb-2"><strong>会社名：</strong>{{ $application->company_name }}</div>
        <div class="mb-2"><strong>会社メール：</strong>{{ $application->company_email }}</div>
        <div class="mb-2"><strong>担当者：</strong>{{ $application->contact_name }}</div>
        <div class="mb-2"><strong>担当者メール：</strong>{{ $application->contact_email }}</div>
        <div class="mb-2"><strong>担当者電話番号：</strong>{{ $application->contact_phone }}</div>
        <div class="mb-2"><strong>説明：</strong>{{ $application->company_description }}</div>
        <div class="mb-2"><strong>状態：</strong>{{ $application->status }}</div>
        <div class="mb-2"><strong>申請日時：</strong>{{ $application->created_at }}</div>
    </div>

    @if($application->status === 'pending')
        <form method="POST" action="{{ route('admin.company_applications.approve', $application->id) }}" class="inline">
            @csrf
            <button class="bg-green-500 text-white px-4 py-2 rounded mr-2">承認</button>
        </form>
        <form method="POST" action="{{ route('admin.company_applications.reject', $application->id) }}" class="inline">
            @csrf
            <button class="bg-red-500 text-white px-4 py-2 rounded">却下</button>
            <textarea name="rejection_reason" rows="3" class="mt-2 block w-full border-gray-300 rounded-md" placeholder="却下理由を入力してください"></textarea>
        </form>
    @elseif($application->status === 'approved')
        <div class="text-green-600 mb-4">この申請は承認済みです。</div>
        <div class="mb-2"><strong>承認日時：</strong>{{ $application->approved_at }}</div>
    @endif

    <a href="{{ route('admin.company_applications.index') }}" class="inline-block mt-6 text-blue-500 hover:underline">← 一覧に戻る</a>
@endsection
