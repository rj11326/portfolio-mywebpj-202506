<p>企業担当者様</p>
<p>新しい求人応募が届きました。</p>
<ul>
    <li>応募者氏名: {{ $application->user->name }}</li>
    <li>求人タイトル: {{ $application->job->title }}</li>
    <li>メッセージ: {{ $application->message }}</li>
</ul>
<p>管理画面で詳細をご確認ください。</p>