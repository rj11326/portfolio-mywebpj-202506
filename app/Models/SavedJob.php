<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    protected $table = 'saved_jobs';
    public $incrementing = false;
    protected $fillable = ['user_id', 'job_id'];
}
