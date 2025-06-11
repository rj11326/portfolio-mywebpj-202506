<p>企業担当者様</p>
<p>申請が承認されました。</p>
<p>以下の情報でログインできます。</p>
<p>ログインURL: <a href="{{ route('company.login') }}">{{ route('company.login') }}</a></p>
<p>ログイン情報:</p>
<ul>
    <li>メールアドレス: {{ $companyUser->email }}</li>
    <li>初期パスワード: {{ $initialPassword }}</li>
</ul>
<p>ログイン後、パスワードの変更をお勧めします。</p>
<p>ご不明点があればお問い合わせください。</p>
<p>よろしくお願いいたします。</p>
<p>求人情報サイト運営事務局</p>
<p>お問い合わせ: <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
<p>電話: {{ config('app.contact_phone') }}</p>
<p>求人情報サイトURL: <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>
<p>※このメールは自動送信です。返信はできません。</p>