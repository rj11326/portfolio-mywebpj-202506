<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'job_category_id',
        'location_id',
        'title',
        'location',
        'salary_min',
        'salary_max',
        'employment_type',
        'description',
        'requirements',
        'welcome_skills',
        'required_qualifications',
        'tools',
        'selection_flow',
        'required_documents',
        'interview_place',
        'benefits',
        'work_time',
        'holiday',
        'number_of_positions',
        'is_active',
        'is_featured',
        'is_closed',
        'application_deadline',
        'auto_reply_message',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'saved_jobs')->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'job_tag');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class);
    }

    protected $casts = [
        'application_deadline' => 'date',
    ];
}
