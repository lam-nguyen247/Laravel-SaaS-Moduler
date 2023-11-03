<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class JwtAdminMiddleware extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            $token = JWTAuth::getToken();
            $payloadToken = JWTAuth::getPayload($token)->toArray();
            
            if ($payloadToken['role'] === UserRole::ADMIN) {
                return $next($request);
            }

            throw new AuthenticationException();
        } catch (Exception $e) {
            throw new AuthenticationException();
        }
    }
}
