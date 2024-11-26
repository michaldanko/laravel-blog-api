<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/auth/token', [AuthController::class, 'generateToken']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);

    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
