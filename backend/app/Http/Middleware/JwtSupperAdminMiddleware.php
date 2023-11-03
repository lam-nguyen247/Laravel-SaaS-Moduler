<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtSupperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = JWTAuth::getToken();
            $user = auth('supper-admin')->user();

            if (empty($token) || empty($user)) {
                throw new AuthenticationException();
            }
            return $next($request);
        } catch (Exception $e) {
            throw new AuthenticationException();
        }
    }
}
