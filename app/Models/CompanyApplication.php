<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyApplication extends Model
{
    protected $fillable = [
        'company_name',
        'company_email',
        'company_description',
        'contact_name',
        'contact_email',
        'contact_phone',
        'status'
    ];
}
