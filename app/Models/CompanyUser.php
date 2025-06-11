<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CompanyUser extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 関連
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}