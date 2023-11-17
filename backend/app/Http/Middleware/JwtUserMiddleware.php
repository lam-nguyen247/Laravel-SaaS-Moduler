<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class JwtUserMiddleware extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request    $request
     * @param  Closure    $next
     * @param  null|mixed $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        try {
            //@phpstan-ignore-next-line
            $token = JWTAuth::getToken();
            //@phpstan-ignore-next-line
            $payloadToken = JWTAuth::getPayload($token)->toArray();
            $user = auth('front-api')->user();

            if ($payloadToken['role'] === UserRole::USER && $user instanceof User) {
                return $next($request);
            }

            throw new AuthenticationException();
        } catch (Exception $e) {
            throw new AuthenticationException();
        }
    }
}
