<?php

use App\Http\Controllers\BodyController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user/{userId}', [PostController::class, 'posts_by_user']);
Route::apiResource('posts', PostController::class);

Route::apiResource('bodies', BodyController::class);
