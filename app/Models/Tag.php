<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'label',
        'slug',
        'sort_order',
    ];

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_tag');
    }
}
