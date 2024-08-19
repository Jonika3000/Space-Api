<?php

use App\Http\Controllers\BodyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts/user/{userId}', [PostController::class, 'posts_by_user']);
Route::apiResource('posts', PostController::class);

Route::get('/comments/post/{postId}', [CommentController::class, 'get_comments_by_post']);
Route::apiResource('comments', CommentController::class);

Route::apiResource('bodies', BodyController::class);
