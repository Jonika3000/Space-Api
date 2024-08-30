<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Helpers\ImageResize;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public static function registerUser(array $data)
    {
        $pathBanner = $data['banner']->store('banners');
        $pathAvatar = $data['avatar']->store('avatars');

        $sizes = [50, 150, 300];
        foreach ($sizes as $size) {
            $pathInfo = pathinfo($pathAvatar);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$size}x{$size}." . $pathInfo["extension"];
            ImageResize::image_resize($size, $size, storage_path('app/public/' . $resizedPath), $data['avatar']);
        }

        $user = User::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'birthday' => $data['birthday'],
            'banner_path' => $pathBanner,
            'role' => RoleEnum::USER,
            'avatar_path' => $pathAvatar,
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $user;
    }

}
