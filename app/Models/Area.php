<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'sort_order'
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
