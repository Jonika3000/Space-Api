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
    public static function registerUser(RegisterRequest $request)
    {
        $pathBanner = $request->file('banner')->store('banners');
        $pathAvatar = $request->file('avatar')->store('avatars');

        $sizes = [50, 150,300];
        foreach($sizes as $size) {
            $pathInfo = pathinfo($pathAvatar);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$size}x{$size}." . $pathInfo["extension"];
            ImageResize::image_resize($size, $size, storage_path('app/public/' . $resizedPath), $request->file('avatar'));
        }

        $user = User::create([
            'login' => $request->login,
            'email' => $request->email,
            'birthday' => $request->birthday,
            'banner_path' => $pathBanner,
            'role' => RoleEnum::USER,
            'avatar_path' => $pathAvatar,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);
        return $user;
    }

}
