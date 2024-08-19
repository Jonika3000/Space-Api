<?php

namespace App\Services;

use App\Models\Image;
use App\Models\PostImage;

class ImageSaveService
{
    public function saveArrayImages(array $images, $postId) {
        $postImages = [];
        foreach ($images as $imageFile) {
            $path = $imageFile->store('images', 'public');
            $image = Image::create(['path' => $path]);
            $postImages[] = [
                'post_id' => $postId,
                'image_id' => $image->id,
            ];
        }
        PostImage::insert($postImages);
    }
}
