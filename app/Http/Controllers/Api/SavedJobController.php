<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class SavedJobController extends Controller
{

    /**
     * 保存された求人のID一覧を取得
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\Http\JsonResponse 保存された求人IDのJSONレスポンス
     */
    public function listIds(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['saved_job_ids' => []]);
        }
        $ids = $user->savedJobs()->pluck('jobs.id');
        return response()->json(['saved_job_ids' => $ids]);
    }


    /**
     * 求人の保存状態を切り替え
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @param Job $job 求人モデルインスタンス
     * @return \Illuminate\Http\JsonResponse 保存状態の切り替え結果
     */
    public function toggle(Request $request, Job $job)
    {
        // ユーザーが認証されているか確認
        // リクエストからユーザー情報を取得
        // ユーザーが認証されていない場合は401エラーを返す
        $user = $request->user();
        if (!$user) {
            return response()->json(['result' => 'unauthenticated'], 401);
        }
        
        // ユーザーが保存した求人に既に存在する場合は
        // その求人を保存から削除し、'removed'を返す
        // そうでない場合は求人を保存し、'saved'を返す
        // ユーザーが保存した求人の状態を切り替える
        if ($user->savedJobs()->where('job_id', $job->id)->exists()) {
            $user->savedJobs()->detach($job->id);
            return response()->json(['result' => 'removed']);
        } else {
            $user->savedJobs()->attach($job->id);
            return response()->json(['result' => 'saved']);
        }
    }
}
