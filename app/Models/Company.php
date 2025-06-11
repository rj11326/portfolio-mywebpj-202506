<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'description',
        'founded_at',
        'capital',
        'employee_count',
    ];

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function applications()
    {
        return $this->hasManyThrough(Application::class, Job::class);
    }

    public function users()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function images()
    {
        return $this->hasMany(CompanyImage::class)->orderBy('order');
    }
}
