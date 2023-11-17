<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\Admin;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Client\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

class JwtAdminMiddleware extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  Request    $request
     * @param  \Closure   $next
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
            $admin = auth('admin-api')->user();

            if ($payloadToken['role'] === UserRole::ADMIN && $admin instanceof Admin) {
                return $next($request);
            }

            throw new AuthenticationException();
        } catch (Exception $e) {
            throw new AuthenticationException();
        }
    }
}
