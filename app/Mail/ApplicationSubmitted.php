<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    /**
     * コンストラクタで新しいメールインスタンスを作成
     * 
     * @since 1.0.0
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * メールのエンベロープを構築
     * 
     * @since 1.0.0
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '新しい応募が届きました',
        );
    }

    /**
     * メールのコンテンツを構築
     * 
     * @since 1.0.0
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application_submitted',
            with: [
                'application' => $this->application,
            ],
        );
    }

    /**
     * メールの添付ファイルを構築
     * 
     * @since 1.0.0
     *
     * @return array
     */
    public function attachments(): array
    {
        // 添付ファイルが必要な場合はここで定義
        // 現在は添付ファイルなし
        return [];
    }
}

