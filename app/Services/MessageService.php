<?php

namespace App\Services;

use App\Models\Message;
use App\Models\MessageFile;
use Illuminate\Support\Facades\Storage;

class MessageService
{
    // メッセージ保存＋ファイル保存
    public function sendMessage(array $data, $files = [])
    {
        $message = Message::create([
            'application_id' => $data['application_id'],
            'message'        => $data['message'],
            'sender_type'    => $data['sender_type'],
            'sender_id'      => $data['sender_id'],
            'is_read'        => false,
        ]);
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

    // メッセージのAPI整形
    public function formatMessagesForApi($messages, $downloadRouteName)
    {
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

    // ファイルDL
    public function downloadFile(MessageFile $file)
    {
        return Storage::download($file->file_path, $file->file_name);
    }
}
