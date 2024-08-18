<?php

use App\Http\Controllers\BodyController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index']);

    Route::post('/', [PostController::class, 'store']);

    Route::get('/{id}', [PostController::class, 'show']);

    Route::put('/{id}', [PostController::class, 'update']);

    Route::delete('/{id}', [PostController::class, 'destroy']);

    Route::get('/user/{userId}', [PostController::class, 'posts_by_user']);
});

Route::prefix('body')->group(function () {
    Route::get('/', [BodyController::class, 'index']);
});
