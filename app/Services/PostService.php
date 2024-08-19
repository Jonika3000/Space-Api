<?php

namespace App\Services;

use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Auth;

class PostService
{

    public function update(Post $post, UpdatePostRequest $request, ImageSaveService $imageSaveService) {
        if(Auth::id() != $post->user_id){
            throw new \Exception ( 'Unauthorized' );
        }

        $post->update($request->validated());

        if ($request->hasFile('images')) {
            PostImage::where('post_id', $post->id)->delete();
            $imageSaveService->saveArrayImages($request->file('images'), $post->id);
        }
    }
}
