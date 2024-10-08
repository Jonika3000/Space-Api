<?php

use App\Http\Controllers\BodyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GalaxyController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts/user/{userId}', [PostController::class, 'postsByUser']);
Route::apiResource('posts', PostController::class);

Route::get('/comments/post/{postId}', [CommentController::class, 'getCommentsByPost']);
Route::apiResource('comments', CommentController::class);

Route::apiResource('bodies', BodyController::class);

Route::apiResource('galaxies', GalaxyController::class);
