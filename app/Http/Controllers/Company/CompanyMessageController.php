<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MessageFile;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyMessageController extends Controller
{

    /**
     * 企業の応募に対するメッセージ一覧を取得
     *
     * @since 1.0.0
     *
     * @param int $applicationId 応募ID
     * @param MessageService $messageService メッセージサービス
     * @return \Illuminate\Http\JsonResponse メッセージ一覧のJSONレスポンス
     */
    public function show($applicationId, MessageService $messageService)
    {
        $company = Auth::user();
        $application = Application::with(['job.company'])
            ->where('id', $applicationId)
            ->whereHas('job', fn($q) => $q->where('company_id', $company->company_id))
            ->firstOrFail();

        $messages = $application->messages()
            ->with(['senderUser', 'senderCompany', 'files'])
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'messages' => $messageService->formatMessagesForApi($messages, 'company.messages.download')
        ]);
    }

    /**
     * 応募に対するメッセージを送信
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $applicationId 応募ID
     * @param MessageService $messageService メッセージサービス
     * @return \Illuminate\Http\JsonResponse 送信後のメッセージ一覧のJSONレスポンス
     */
    public function store(Request $request, $applicationId, MessageService $messageService)
    {
        $company = Auth::user();
        $application = Application::where('id', $applicationId)
            ->whereHas('job', fn($q) => $q->where('company_id', $company->company_id))
            ->firstOrFail();

        $request->validate([
            'body'     => 'required|string|max:2000',
            'files.*'  => 'nullable|file|max:10240',
        ]);

        $data = [
            'application_id' => $applicationId,
            'message'        => $request->input('body'),
            'sender_type'    => 1, // 企業
            'sender_id'      => $company->id,
        ];

        $messageService->sendMessage($data, $request->file('files', []));

        $messages = $application->messages()
            ->with(['senderUser', 'senderCompany', 'files'])
            ->orderBy('created_at')->get();

        return response()->json([
            'messages' => $messageService->formatMessagesForApi($messages, 'company.messages.download')
        ]);
    }


    /**
     * Summary of downloadFile
     * 
     * @since 1.0.0
     * 
     * @param int $fileId ファイルID
     * @param \App\Services\MessageService $messageService メッセージサービス
     * @return \Symfony\Component\HttpFoundation\StreamedResponse ファイルのダウンロードレスポンス
     */
    public function downloadFile($fileId, MessageService $messageService)
    {
        // 認証された企業ユーザーの情報を取得
        $file = MessageFile::findOrFail($fileId);

        // メッセージファイルから応募情報を取得
        $application = $file->message->application;

        // 企業情報を取得
        $company = Auth::user();

        // 認可
        if ($application->job->company_id !== $company->company_id) {
            abort(403);
        }

        // ファイルのダウンロード処理をメッセージサービスに委譲
        return $messageService->downloadFile($file);
    }
}
