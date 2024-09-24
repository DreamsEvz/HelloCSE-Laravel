<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('profiles/active', [ProfileController::class, 'getActiveProfiles']);
Route::apiResource('profiles', ProfileController::class);