<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use App\Services\MessageService;

class CompanyApplicationController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::guard('company')->user()->company;

        // フィルタ値取得
        $jobTitle = $request->input('job_title');
        $userName = $request->input('user_name');
        $status = $request->input('status');
        $jobIdFilter = $request->input('job_id');

        $query = Application::with(['user', 'job'])
            ->whereIn('job_id', $company->jobs()->pluck('id'));

        if ($jobIdFilter) {
            $query->where('job_id', $jobIdFilter);
        }
        if ($jobTitle) {
            $query->whereHas('job', fn($q) => $q->where('title', 'like', "%$jobTitle%"));
        }
        if ($userName) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$userName%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(20)->appends($request->query());

        $jobs = $company->jobs()->orderBy('title')->get();
        $statuses = config('const.application_statuses');

        return view('company.applications.index', compact('applications', 'jobs', 'statuses', 'jobTitle', 'userName', 'status', 'jobIdFilter'));
    }

    public function show($id)
    {
        $application = Application::with([
            'user.workHistories',
            'user.educations',
            'user.licenses',
            'job'
        ])->findOrFail($id);

        $companyId = Auth::guard('company')->user()->company_id;

        // メッセージ一覧も取得
        $messages = $application->messages()
            ->with(['files', 'senderUser', 'senderCompany'])
            ->orderBy('created_at')
            ->get();

        return view('company.applications.show', compact('application', 'messages'));
    }

    public function status(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $company = Auth::guard('company')->user()->company;

        $request->validate([
            'status' => 'required|integer|in:0,1,2,3,4,5,6',
        ]);

        $application->update(['status' => $request->input('status')]);

        return redirect()->route('company.applications.index')->with('success', '応募ステータスを更新しました');
    }

    public function downloadAllFiles($applicationId)
    {
        $application = Application::with('files')->findOrFail($applicationId);

        if ($application->files->isEmpty()) {
            return back()->with('error', '添付ファイルがありません。');
        }

        $zipFileName = 'application_files_' . $application->id . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipFileName);

        // 一時ディレクトリ作成
        if (!file_exists(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0777, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($application->files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->original_name ?? basename($file->file_path));
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'ZIPファイルの作成に失敗しました。');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
