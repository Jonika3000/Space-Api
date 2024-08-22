<?php

namespace App\Services;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function __construct(private ImageSaveService $imageSaveService){}

    public function store(StorePostRequest $request)
    {
        $post = $request->user()->posts()->create($request->validated());
        if ($request->hasFile('images')) {
            $this->imageSaveService->saveArrayImages($request->file('images'), $post->id);
        }

        return $post;
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $post->update($request->validated());

        if ($request->hasFile('images')) {
            PostImage::where('post_id', $post->id)->delete();
            $this->imageSaveService->saveArrayImages($request->file('images'), $post->id);
        }
    }
}
