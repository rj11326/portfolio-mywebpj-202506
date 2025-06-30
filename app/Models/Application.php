<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'job_id',
        'company_id',
        'status',
        'message',
        'motivation',
        'resume_path'
    ];
    
    protected $casts = [
        'status' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function files()
    {
        return $this->hasMany(ApplicationFile::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
