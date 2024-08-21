<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->role != RoleEnum::ADMIN->value && $user->role != RoleEnum::EDITOR->value) {
            return response()->json(['message' => 'Unauthorized'], \Illuminate\Http\Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
