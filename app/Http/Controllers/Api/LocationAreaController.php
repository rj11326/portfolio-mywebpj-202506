<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;

class LocationAreaController extends Controller
{

    /**
     * 地域とその下位のロケーションを取得
     *
     * @since 1.0.0
     *
     * @return \Illuminate\Http\JsonResponse 地域とロケーションのJSONレスポンス
     */
    public function index()
    {
        // 地域とその下位のロケーションを取得
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
