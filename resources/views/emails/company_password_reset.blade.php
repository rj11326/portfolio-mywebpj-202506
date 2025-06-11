<p>{{ $username }} 様</p>
<p>企業管理システムのパスワードがリセットされました。<br>新しい仮パスワードは下記の通りです。</p>
<p><strong>新しいパスワード：</strong><br><span style="font-size: 1.2em; letter-spacing: 2px;">{{ $initialPassword }}</span></p>
<p>ログイン後、必ずパスワードの変更をお願いいたします。<br><a href="{{ url('/company/login') }}">{{ url('/company/login') }}</a></p>
<p>※このメールは自動送信されています。ご不明な点がございましたら管理者までご連絡ください。</p>