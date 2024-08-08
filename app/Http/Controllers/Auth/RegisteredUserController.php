<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Helpers\ImageResize;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'banner' => ['required', 'mimes:jpeg,png,jpg,gif,svg'],
            'avatar' => ['required', 'mimes:jpeg,png,jpg,gif,svg'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $pathBanner = $request->file('banner')->store('images');
        $pathAvatar = $request->file('avatar')->store('images');

        $sizes = [50, 150,300];
        foreach($sizes as $size)
        {
            $pathInfo = pathinfo($pathAvatar);
            $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$size}x{$size}." . $pathInfo["extension"];
            ImageResize::image_resize($size, $size, storage_path('app/' . $resizedPath), 'avatar');
        }

        $user = User::create([
            'login' => $request->login,
            'email' => $request->email,
            'banner_path' => $pathBanner,
            'role' => RoleEnum::USER,
            'avatar_path' => $pathAvatar,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json(['message'=> 'successfully'], Response::HTTP_CREATED);
    }
}
