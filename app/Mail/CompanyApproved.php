<?php

namespace App\Mail;

use App\Models\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $companyUser;
    public $initialPassword;

    public function __construct(CompanyUser $companyUser, string $initialPassword)
    {
        $this->companyUser = $companyUser;
        $this->initialPassword = $initialPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【企業申請承認】申請が承認されました',
        );
    }

    public function content(): Content
    {
        // メールの内容を定義
        return new Content(
            view: 'emails.company_approved',
            with: [
                'companyUser' => $this->companyUser,
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

