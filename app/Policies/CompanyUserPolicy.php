<?php

namespace App\Policies;

use App\Models\CompanyUser;

class CompanyUserPolicy
{
    public function viewAny(CompanyUser $user): bool
    {
        return $user->role === 1;
    }

    public function view(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }

    public function create(CompanyUser $user): bool
    {
        return $user->role === 1;
    }

    public function edit(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }

    public function update(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }

    public function passwordReset(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }

    public function delete(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }

    public function restore(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }

    public function forceDelete(CompanyUser $user, CompanyUser $companyUser): bool
    {
        return $user->role === 1;
    }
}