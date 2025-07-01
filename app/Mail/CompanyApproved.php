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

    /**
     * コンストラクタで新しいメールインスタンスを作成
     * 
     * @since 1.0.0
     *
     * @param CompanyUser $companyUser 企業ユーザーインスタンス
     * @param string $initialPassword 初期パスワード
     */
    public function __construct(CompanyUser $companyUser, string $initialPassword)
    {
        $this->companyUser = $companyUser;
        $this->initialPassword = $initialPassword;
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
            subject: '【企業申請承認】申請が承認されました',
        );
    }

    /**
     * メールの内容を定義
     * 
     * @since 1.0.0
     *
     * @return Content
     */
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

