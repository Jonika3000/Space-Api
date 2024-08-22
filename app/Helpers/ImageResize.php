<?php

namespace App\Helpers;
use Illuminate\Http\UploadedFile;

class ImageResize
{
    public static function image_resize($width, $height, $path, UploadedFile $file)
    {
        list($w, $h) = getimagesize($file->getPathname());
        $maxSize = max($width, $height);

        $ratioOrig = $w / $h;
        if ($width / $height > $ratioOrig) {
            $width = $height * $ratioOrig;
        } else {
            $height = $width / $ratioOrig;
        }

        $imgString = file_get_contents($file->getPathname());
        $image = imagecreatefromstring($imgString);

        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $tmp,
            $image,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $w,
            $h
        );

        switch ($file->getMimeType()) {
            case 'image/jpeg':
                imagejpeg($tmp, $path, 30);
                break;
            case 'image/png':
                imagepng($tmp, $path, 0);
                break;
            case 'image/gif':
                imagegif($tmp, $path);
                break;
        }

        imagedestroy($image);
        imagedestroy($tmp);

        return $path;
    }
}
