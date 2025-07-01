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

    /**
     * 企業の応募一覧
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View 応募一覧ページのビュー
     */
    public function index(Request $request)
    {
        // 企業情報取得
        $company = Auth::guard('company')->user()->company;

        // フィルタ値取得
        $jobTitle = $request->input('job_title');
        $userName = $request->input('user_name');
        $status = $request->input('status');
        $jobIdFilter = $request->input('job_id');

        // 応募を取得
        $query = Application::with(['user', 'job'])
            ->whereIn('job_id', $company->jobs()->pluck('id'));

        // フィルタリング
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

        // ページネーションとソート
        $applications = $query->latest()->paginate(20)->appends($request->query());

        // 企業の求人一覧を取得
        $jobs = $company->jobs()->orderBy('title')->get();
        // 応募ステータスの定義を取得
        $statuses = config('const.application_statuses');

        return view('company.applications.index', compact('applications', 'jobs', 'statuses', 'jobTitle', 'userName', 'status', 'jobIdFilter'));
    }

    /**
     * 応募詳細
     *
     * @since 1.0.0
     *
     * @param int $id 応募ID
     * @return \Illuminate\View\View 応募詳細ページのビュー
     */
    public function show($id)
    {
        // 応募データを取得
        $application = Application::with([
            'user.workHistories',
            'user.educations',
            'user.licenses',
            'job'
        ])->findOrFail($id);

        // 企業IDを取得
        $companyId = Auth::guard('company')->user()->company_id;

        // メッセージ一覧も取得
        $messages = $application->messages()
            ->with(['files', 'senderUser', 'senderCompany'])
            ->orderBy('created_at')
            ->get();

        return view('company.applications.show', compact('application', 'messages'));
    }

    /**
     * 応募ステータスを更新
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param int $id 応募ID
     * @return \Illuminate\Http\RedirectResponse ステータス更新後のリダイレクトレスポンス
     */
    public function status(Request $request, $id)
    {
        // 応募データを取得
        $application = Application::findOrFail($id);
        // 企業情報取得
        $company = Auth::guard('company')->user()->company;

        // バリデーション
        $request->validate([
            'status' => 'required|integer|in:0,1,2,3,4,5,6',
        ]);

        // ステータスを更新
        $application->update(['status' => $request->input('status')]);

        return redirect()->route('company.applications.index')->with('success', '応募ステータスを更新しました');
    }

    /**
     * 応募者の全ファイルをZIPでダウンロード
     * 
     * @since 1.0.0
     * 
     * @param int $applicationId　応募ID
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse ファイルのダウンロードレスポンス
     */
    public function downloadAllFiles($applicationId)
    {
        // 応募データを取得
        $application = Application::with('files')->findOrFail($applicationId);

        // 添付ファイルが存在しない場合はエラーメッセージを返す
        if ($application->files->isEmpty()) {
            return back()->with('error', '添付ファイルがありません。');
        }

        // ZIPファイルのパスと名前を設定
        // 一時ディレクトリに保存するため、storage/app/tmpに保存
        // ファイル名は応募IDを含むようにする
        // 例: application_files_123.zip
        $zipFileName = 'application_files_' . $application->id . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipFileName);

        // 一時ディレクトリ作成
        if (!file_exists(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0777, true);
        }

        // ZIPファイルを作成
        // ZipArchiveを使用して応募者の全ファイルをZIPにまとめる
        // ファイルはstorage/app/publicに保存されている前提
        // ファイル名は元の名前を保持するため、original_nameがあればそれを使用
        // なければファイルパスのベース名を使用
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
