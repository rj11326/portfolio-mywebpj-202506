<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MessageFile;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // スレッド一覧＋最初のメッセージ
    public function index(Request $request)
    {
        $user = Auth::user();
        $threads = Application::with(['job.company', 'messages' => fn($q) => $q->latest()])
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();
        $selectedThread = $threads->first();
        $messages = $selectedThread
            ? $selectedThread->messages()->with(['senderUser', 'senderCompany', 'files'])->orderBy('created_at')->get()
            : collect();

        return view('messages.index', [
            'threads'        => $threads,
            'selectedThread' => $selectedThread,
            'messages'       => $messages,
        ]);
    }

    // メッセージ一覧取得
    public function show($applicationId, MessageService $messageService)
    {
        $user = Auth::user();
        $application = Application::with(['job.company'])
            ->where('id', $applicationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $messages = $application->messages()
            ->with(['senderUser', 'senderCompany', 'files'])
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $messageService->formatMessagesForApi($messages, 'messages.download')
        ]);
    }

    // メッセージ送信
    public function store(Request $request, $applicationId, MessageService $messageService)
    {
        $user = Auth::user();
        $application = Application::where('id', $applicationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'body'     => 'required|string|max:2000',
            'files.*'  => 'nullable|file|max:10240',
        ]);

        $data = [
            'application_id' => $applicationId,
            'message'        => $request->input('body'),
            'sender_type'    => 0, // ユーザー
            'sender_id'      => $user->id,
        ];

        $messageService->sendMessage($data, $request->file('files', []));

        // 新着取得
        $messages = $application->messages()
            ->with(['senderUser', 'senderCompany', 'files'])
            ->orderBy('created_at')->get();

        return response()->json([
            'messages' => $messageService->formatMessagesForApi($messages, 'messages.download')
        ]);
    }

    // ファイルDL
    public function downloadFile($fileId, MessageService $messageService)
    {
        $file = MessageFile::findOrFail($fileId);
        $application = $file->message->application;
        $user = Auth::user();

        // 認可
        if ($application->user_id !== $user->id) {
            abort(403);
        }

        return $messageService->downloadFile($file);
    }
}
