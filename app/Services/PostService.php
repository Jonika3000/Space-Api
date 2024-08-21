<?php

namespace App\Services;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostImage;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Auth;

class PostService
{
    private $imageSaveService;
    private $postRepository;

    public function __construct(ImageSaveService $imageSaveService, PostRepository $postRepository)
    {
        $this->imageSaveService = $imageSaveService;
        $this->postRepository = $postRepository;
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        $this->postRepository->update($request, $post);

        if ($request->hasFile('images')) {
            PostImage::where('post_id', $post->id)->delete();
            $this->imageSaveService->saveArrayImages($request->file('images'), $post->id);
        }
    }

    public function store(StorePostRequest $request){
        $post = $this->postRepository->store($request->validated());
        if ($request->hasFile('images')) {
            $this->imageSaveService->saveArrayImages($request->file('images'), $post->id);
        }

        return $post;
    }
}
