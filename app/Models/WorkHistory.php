<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkHistory extends Model
{
    protected $fillable = ['user_id', 'job_title', 'company_name', 'location', 'position', 'is_current', 'start_date', 'end_date', 'description'];
}
