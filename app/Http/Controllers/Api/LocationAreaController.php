<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;

class LocationAreaController extends Controller
{
    public function index()
    {
        $areas = Area::with(['locations' => function($q) {
            $q->orderBy('sort_order');
        }])
        ->orderBy('sort_order')
        ->get()
        ->map(function ($area) {
            return [
                'id' => $area->id,
                'name' => $area->name,
                'slug' => $area->slug,
                'children' => $area->locations->map(function ($location) {
                    return [
                        'id' => $location->id,
                        'name' => $location->name,
                        'slug' => $location->slug,
                    ];
                })->toArray()
            ];
        });

        return response()->json(['areas' => $areas]);
    }
}
