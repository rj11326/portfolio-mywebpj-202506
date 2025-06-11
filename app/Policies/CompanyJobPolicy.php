<?php

namespace App\Policies;

use App\Models\CompanyUser;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;

class CompanyJobPolicy
{
    /**
     * 求人の編集・更新・公開/非公開・複製・募集終了
     */
    public function update($user, Job $job)
    {
        // 自社求人のみ許可
        if (isset($user->company_id) && $user->company_id === $job->company_id) {
            return true;
        }
        return false;
    }

    /**
     * 求人の閲覧（応募者一覧・応募者詳細含む）
     */
    public function view($user, Job $job)
    {
        // 自社求人のみ許可
        if (isset($user->company_id) && $user->company_id === $job->company_id) {
            return true;
        }
        return false;
    }

    /**
     * 応募者詳細の閲覧
     */
    public function viewApplication($user, Application $application)
    {
        // 自社求人の応募のみ許可
        if (isset($user->company_id) && $application->job && $user->company_id === $application->job->company_id) {
            return true;
        }
        return false;
    }
}