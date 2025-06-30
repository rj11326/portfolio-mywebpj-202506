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
    // メッセージ一覧取得
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

    // メッセージ送信
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

    // ファイルDL
    public function downloadFile($fileId, MessageService $messageService)
    {
        $file = MessageFile::findOrFail($fileId);
        $application = $file->message->application;
        $company = Auth::user();

        // 認可
        if ($application->job->company_id !== $company->company_id) {
            abort(403);
        }

        return $messageService->downloadFile($file);
    }
}
