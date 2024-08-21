<?php

namespace App\Interfaces;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;

interface PostRepositoryInterface
{
    public function index();
    public function store(StorePostRequest $request);
    public function show(Post $model);
    public function update(UpdatePostRequest $request, Post $post);
    public function destroy(Post $post);
    public function posts_by_user(int $user_id);
}
