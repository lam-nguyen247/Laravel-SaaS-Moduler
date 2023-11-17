<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Requests\LoginRequest;
use App\Modules\Admin\Requests\ChangePasswordRequest;
use App\Modules\Admin\Requests\ChangeProfileRequest;
use App\Models\Admin;
use App\Modules\Admin\Services\AdminService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthenticateController extends Controller
{
    private AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Login
     *
     * @param  LoginRequest                  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = $this->adminService->verifyLogin($credentials)) {
            return $this->errorApiResponse(Response::HTTP_UNAUTHORIZED, 'User not found');
        }

        return $this->respondWithToken($token, 'admin-api');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->getSuccess(auth('admin-api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('admin-api')->logout();

        return $this->getSuccess();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        //@phpstan-ignore-next-line
        return $this->respondWithToken(auth('admin-api')->refresh(), 'admin-api');
    }

    /**
     * Change password.
     *
     * @param  ChangePasswordRequest         $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = $request->user('admin-api');

        if (!$this->adminService->changePassword($user, $credentials)) {
            return response()->json([
                'message' => 'The old password field is incorrect',
                'errors' => [
                    'old_password' => ['The old password field is incorrect'],
                ],

            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->getSuccess([]);
    }

    /**
     * Change profile.
     *
     * @param  ChangeProfileRequest          $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeProfile(ChangeProfileRequest $request): JsonResponse
    {
        $admin = $request->user('admin-api');

        $response = $this->adminService->update($admin, $request->validated());

        if ($response instanceof Admin) {
            return $this->getSuccess($response);
        }

        throw new Exception();
    }
}
