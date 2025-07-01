<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageFile;
use Illuminate\Support\Facades\Storage;

class MessageService
{
    /**
     * メッセージを保存し、関連するファイルも保存
     * 
     * @since 1.0.0
     *
     * @param array $data メッセージデータ
     * @param array $files 添付ファイル
     * @return Message 保存されたメッセージインスタンス
     */
    public function sendMessage(array $data, $files = [])
    {
        // メッセージを保存
        $message = Message::create([
            'application_id' => $data['application_id'],
            'message'        => $data['message'],
            'sender_type'    => $data['sender_type'],
            'sender_id'      => $data['sender_id'],
            'is_read'        => false,
        ]);

        // 添付ファイルがある場合は保存
        if ($files && is_array($files)) {
            foreach ($files as $file) {
                $path = $file->store('message_files');
                MessageFile::create([
                    'message_id' => $message->id,
                    'file_path'  => $path,
                    'file_name'  => $file->getClientOriginalName(),
                    'file_type'  => $file->getClientMimeType(),
                    'file_size'  => $file->getSize(),
                ]);
            }
        }
        return $message;
    }

    /**
     * メッセージをAPI用に整形
     * 
     * @since 1.0.0
     *
     * @param \Illuminate\Support\Collection $messages メッセージコレクション
     * @param string $downloadRouteName ファイルダウンロードのルート名
     * @return \Illuminate\Support\Collection 整形されたメッセージコレクション
     */
    public function formatMessagesForApi($messages, $downloadRouteName)
    {
        // メッセージを整形
        return $messages->map(function ($message) use ($downloadRouteName) {
            return [
                'id'         => $message->id,
                'sender_type'=> $message->sender_type,
                'sender_id'  => $message->sender_id,
                'message'    => $message->message,
                'is_read'    => $message->is_read,
                'created_at' => $message->created_at->format('Y/m/d H:i'),
                'files'      => $message->files->map(function ($file) use ($downloadRouteName) {
                    return [
                        'id'        => $file->id,
                        'file_name' => $file->file_name,
                        'url'       => route($downloadRouteName, $file->id),
                        'file_type' => $file->file_type,
                        'file_size' => $file->file_size,
                    ];
                }),
            ];
        });
    }

    /**
     * Summary of downloadFile
     * 
     * @since 1.0.0
     * 
     * @param \App\Models\MessageFile $file ファイルモデル
     * @return \Symfony\Component\HttpFoundation\StreamedResponse ファイルのダウンロードレスポンス
     */
    public function downloadFile(MessageFile $file)
    {
        return Storage::download($file->file_path, $file->file_name);
    }
}
