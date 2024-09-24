<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Route permettant de récupérer les profils actifs qui n'est pas protégée par le middleware d'auth
Route::get('profiles/active', [ProfileController::class, 'getActiveProfiles']);
//Permet de générer automatiquement les routes pour un CRUD complet, celles-ci sont protégées par le middleware d'auth
Route::apiResource('profiles', ProfileController::class);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');