<?php

namespace App\Mail;

use App\Models\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $initialPassword;

    public function __construct(string $username, string $initialPassword)
    {
        $this->username = $username;
        $this->initialPassword = $initialPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【パスワードリセット】新しいパスワードが設定されました',
        );
    }

    public function content(): Content
    {
        // メールの内容を定義
        return new Content(
            view: 'emails.company_password_reset',
            with: [
                'username' => $this->username,
                'initialPassword' => $this->initialPassword,
            ],
        );
    }

    public function attachments(): array
    {
        // 添付ファイルが必要な場合はここで定義
        // 現在は添付ファイルなし
        return [];
    }
}

