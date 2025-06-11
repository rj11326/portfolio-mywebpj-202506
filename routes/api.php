<?php
use Illuminate\Http\Request;
use App\Http\Controllers\Api\JobApiController;
use App\Http\Controllers\Api\TagApiController;
use App\Http\Controllers\Api\JobCategoryApiController;
use App\Http\Controllers\Api\LocationAreaController;
use App\Http\Controllers\Api\SavedJobController;

Route::get('/jobs', [JobApiController::class, 'index']);
Route::get('/tags', [TagApiController::class, 'index']);
Route::get('/job-categories', [JobCategoryApiController::class, 'index']);
Route::get('/location-areas', [LocationAreaController::class, 'index']);
Route::get('/saved-jobs', [SavedJobController::class, 'listIds']);
Route::middleware('auth:sanctum')->post('/jobs/{job}/save', [SavedJobController::class, 'toggle']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});