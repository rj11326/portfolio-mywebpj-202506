<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    protected $fillable = [
        'name', 'slug', 'area_id', 'sort_order'
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
