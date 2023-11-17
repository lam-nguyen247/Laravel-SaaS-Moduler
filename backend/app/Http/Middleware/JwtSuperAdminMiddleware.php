<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtSuperAdminMiddleware
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            //@phpstan-ignore-next-line
            $token = JWTAuth::getToken();
            $user = auth('super-admin')->user();

            if (empty($token) || empty($user)) {
                throw new AuthenticationException();
            }

            return $next($request);
        } catch (Exception $e) {
            throw new AuthenticationException();
        }
    }
}
