<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'file_path',
        'order',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getUrlAttribute()
    {
        return \Storage::disk('public')->url($this->path);
    }
}
