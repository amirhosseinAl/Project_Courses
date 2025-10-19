<?php

namespace Modules\User\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {

        if (!($token = $request->bearerToken('Authorization'))) {
            return response()->json(['message' => __('user::validation.token_error')]);
        }

        if (!($accessToken = PersonalAccessToken::findToken($token))) {
            return response()->json(['message' => __('user::validation.token_invalid')]);
        }

        $request->setUserResolver(function () use ($accessToken) {
            return $accessToken->tokenable;
        });

        $user = $accessToken->tokenable;
        Auth::setUser($user);


        if (!empty($roles) && !in_array($user->roles, $roles)) {
            return response()->json(['message' => __('user::validation.access')]);
        }

        return $next($request);
    }
}
