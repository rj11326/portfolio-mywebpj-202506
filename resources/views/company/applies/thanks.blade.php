@extends('layouts.company')

@section('title', '企業アカウント申請完了')

@section('content')
<div class="max-w-lg mx-auto py-16 text-center">
    <h1 class="text-2xl font-bold mb-6 text-green-700">企業アカウント申請を受け付けました</h1>

    <div class="mb-8 text-gray-700 text-base leading-relaxed">
        ご申請いただき、誠にありがとうございます。<br>
        内容を運営事務局にて確認後、メールにてご連絡差し上げます。
    </div>

    <div class="bg-gray-50 p-6 rounded-lg border mb-8 text-left text-sm text-gray-800">
        <div class="font-semibold mb-2 text-gray-900">【今後の流れ】</div>
        <ol class="list-decimal ml-6 space-y-2">
            <li>
                <span class="font-medium">審査：</span>ご入力内容を運営が確認し、審査を行います。
            </li>
            <li>
                <span class="font-medium">承認・ご連絡：</span>審査の結果はご登録のメールアドレス宛にご案内いたします。<br>
                （通常1〜2営業日以内）
            </li>
            <li>
                <span class="font-medium">アカウント発行：</span>承認された場合は、企業アカウントのログイン情報をお送りします。
            </li>
            <li>
                <span class="font-medium">企業ログイン：</span>メール記載の情報でログイン後、求人情報の登録などが可能となります。
            </li>
        </ol>
        <div class="mt-4 text-gray-500">
            ※審査状況やご不明点は、お問い合わせフォームよりご連絡ください。
        </div>
    </div>

    <a href="{{ route('home') }}"
       class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-2 rounded-full text-base font-semibold transition">
        トップページへ戻る
    </a>
</div>
@endsection
