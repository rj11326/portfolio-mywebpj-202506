<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];

    // 親カテゴリ
    public function parent()
    {
        return $this->belongsTo(JobCategory::class, 'parent_id');
    }
    // 子カテゴリ
    public function children()
    {
        return $this->hasMany(JobCategory::class, 'parent_id');
    }
    
}
