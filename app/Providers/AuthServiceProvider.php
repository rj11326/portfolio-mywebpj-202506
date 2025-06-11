<?php

namespace App\Providers;

use App\Models\WorkHistory;
use App\Models\Education;
use App\Models\License;
use App\Models\CompanyUser;
use App\Models\Job;
use App\Policies\CompanyJobPolicy;
use App\Policies\CompanyUserPolicy;
use App\Policies\EducationPolicy;
use App\Policies\LicensePolicy;
use App\Policies\WorkHistoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * モデルとポリシーのマッピング
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Job::class => CompanyJobPolicy::class,
        CompanyUser::class => CompanyUserPolicy::class,
        WorkHistory::class => WorkHistoryPolicy::class,
        Education::class => EducationPolicy::class,
        License::class => LicensePolicy::class,
    ];

    /**
     * アプリケーションの認可サービスを登録
     */
    public function boot(): void
    {
        $this->registerPolicies();
        
        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

    }
}
