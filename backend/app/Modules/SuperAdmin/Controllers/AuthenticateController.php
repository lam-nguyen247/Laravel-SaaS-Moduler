<?php

namespace App\Modules\SuperAdmin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SuperAdmin\Requests\AccountSuperAdmin\ChangePasswordRequest;
use App\Modules\SuperAdmin\Requests\AccountSuperAdmin\LoginRequest;
use App\Modules\SuperAdmin\Requests\AccountSuperAdmin\UpdateRequest;
use App\Modules\SuperAdmin\Services\SuperAdminService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthenticateController extends Controller
{
    use ResponseTrait;

    public const AUTH_CODE = 'super-admin';

    private SuperAdminService $superAdminService;

    public function __construct(SuperAdminService $superAdminService)
    {
        $this->superAdminService = $superAdminService;
    }

    /**
     * Login by email and password
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $input = $request->all();

            if (!$token = auth(self::AUTH_CODE)->attempt($input)) {
                return $this->errorApiResponse(Response::HTTP_UNAUTHORIZED, 'Email or password incorrect');
            }

            return $this->respondWithToken($token, self::AUTH_CODE);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Get super admin info
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->successApiResponse('Get successful', auth(self::AUTH_CODE)->user());
    }

    /**
     * Refresh token
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        //@phpstan-ignore-next-line
        return $this->respondWithToken(auth(self::AUTH_CODE)->refresh(), self::AUTH_CODE);
    }

    /**
     * Logout
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth(self::AUTH_CODE)->logout();

            return $this->successApiResponse('Successfully logged out', []);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Change password.
     *
     * @param  ChangePasswordRequest $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $userLogin = auth(self::AUTH_CODE)->user();

            if (!$this->superAdminService->isComparePassword($input['old_password'], $userLogin->password)) {
                return $this->errorApiResponse(Response::HTTP_UNAUTHORIZED, 'Old password not match');
            }
            $this->superAdminService->changePassword($userLogin, $input['new_password']);

            return $this->successApiResponse('Change password successful', $userLogin);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Update super admin info
     *
     * @param  UpdateRequest $request
     * @return JsonResponse
     */
    public function update(UpdateRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $userLogin = auth(self::AUTH_CODE)->user();

            $this->superAdminService->update($userLogin, $input);

            return $this->successApiResponse('Update successful', $userLogin);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }
}
