<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MessageFile;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * メッセージ一覧を表示
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View メッセージ一覧ページのビュー
     */
    public function index(Request $request)
    {
        // 認証されたユーザーのメッセージスレッドを取得
        $user = Auth::user();
        $threads = Application::with(['job.company', 'messages' => fn($q) => $q->latest()])
            ->where('user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get();
        // スレッドが選択されていない場合は最初のスレッドを選択
        $selectedThread = $threads->first();

        // 選択されたスレッドのメッセージを取得
        $messages = $selectedThread
            ? $selectedThread->messages()->with(['senderUser', 'senderCompany', 'files'])->orderBy('created_at')->get()
            : collect();

        return view('messages.index', [
            'threads'        => $threads,
            'selectedThread' => $selectedThread,
            'messages'       => $messages,
        ]);
    }

    /**
     * メッセージ一覧取得
     *
     * @since 1.0.0
     *
     * @param int $applicationId 応募ID
     * @param MessageService $messageService メッセージサービス
     * @return \Illuminate\Http\JsonResponse メッセージ一覧のJSONレスポンス
     */
    public function show($applicationId, MessageService $messageService)
    {

        // 認証されたユーザーの応募情報を取得
        $user = Auth::user();
        $application = Application::with(['job.company'])
            ->where('id', $applicationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // メッセージの取得
        $messages = $application->messages()
            ->with(['senderUser', 'senderCompany', 'files'])
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $messageService->formatMessagesForApi($messages, 'messages.download')
        ]);
    }

    /**
     * メッセージを送信
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $applicationId 応募ID
     * @param MessageService $messageService メッセージサービス
     * @return \Illuminate\Http\JsonResponse 新着メッセージのJSONレスポンス
     */
    public function store(Request $request, $applicationId, MessageService $messageService)
    {
        // 認証されたユーザーの応募情報を取得
        $user = Auth::user();
        $application = Application::where('id', $applicationId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // バリデーション
        $request->validate([
            'body'     => 'required|string|max:2000',
            'files.*'  => 'nullable|file|max:10240',
        ]);

        // メッセージデータの準備
        $data = [
            'application_id' => $applicationId,
            'message'        => $request->input('body'),
            'sender_type'    => 0, // ユーザー
            'sender_id'      => $user->id,
        ];

        // メッセージを送信
        $messageService->sendMessage($data, $request->file('files', []));

        // 新着取得
        $messages = $application->messages()
            ->with(['senderUser', 'senderCompany', 'files'])
            ->orderBy('created_at')->get();

        return response()->json([
            'messages' => $messageService->formatMessagesForApi($messages, 'messages.download')
        ]);
    }

    /**
     * ファイルをダウンロード
     *
     * @since 1.0.0
     * 
     * @param int $fileId ファイルID
     * @param \App\Services\MessageService $messageService メッセージサービス
     * @return \Symfony\Component\HttpFoundation\StreamedResponse ファイルのダウンロードレスポンス
     */
    public function downloadFile($fileId, MessageService $messageService)
    {
        // 認証されたユーザーの応募情報を取得
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
