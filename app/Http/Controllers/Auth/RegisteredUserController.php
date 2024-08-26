<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleEnum;
use App\Helpers\ImageResize;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\RegisterService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(RegisterRequest $request, RegisterService $registerService): JsonResponse
    {
        $registerService->registerUser($request);

        return response()->json(['message' => 'successfully'], Response::HTTP_CREATED);
    }
}
