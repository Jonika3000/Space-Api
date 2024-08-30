<?php

namespace App\Services;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Auth;

class PostService
{
    public function __construct(private ImageService $imageSaveService)
    {
    }

    public function store(array $data)
    {
        $user = Auth::user();

        $post = $user->posts()->create($data);

        if (isset($data['images']) && is_array($data['images'])) {
            $this->imageSaveService->saveArrayImages($data['images'], $post->id);
        }

        return $post;
    }

    public function update(Post $post, array $data)
    {
        $post->update($data);

        if (isset($data['images']) && is_array($data['images'])) {
            PostImage::where('post_id', $post->id)->delete();
            $this->imageSaveService->saveArrayImages($data['images'], $post->id);
        }
    }
}
