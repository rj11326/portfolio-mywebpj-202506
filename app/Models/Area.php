<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = [
        'name', 'slug', 'sort_order'
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }
}
