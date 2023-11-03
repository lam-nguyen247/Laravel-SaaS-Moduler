<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Admin\ChangeProfileRequest;
use App\Models\Admin;
use App\Repositories\Sql\SqlAdminRepository;
use App\Services\AdminService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthenticateController extends Controller
{
    private AdminService $adminService;

    private SqlAdminRepository $sqlAdminRepository;

    public function __construct(AdminService $adminService, SqlAdminRepository $sqlAdminRepository)
    {
        $this->adminService = $adminService;
        $this->sqlAdminRepository = $sqlAdminRepository;
    }

    /**
     * Login
     * 
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('admin-api')->claims([
            'role' => 'admin',
            'email' => $credentials['email']
        ])->attempt($credentials)) {
            return response()->json(['error' => 'User not found'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth('admin-api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('admin-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth('admin-api')->refresh());
    }

    /**
     * Change password.
     * 
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $user = $request->user('admin-api');

        if (!$this->adminService->isComparePassword($credentials['old_password'],  $user->password)) {
            return response()->json([
                'message' => "The old password field is incorrect",
                'errors' => [
                    'old_password' => ['The old password field is incorrect']
                ]
                
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $response = $this->sqlAdminRepository->update($user, [
            'password' => Hash::make($credentials['new_password']),
        ]);

        if ($response instanceof Admin) {
            return response()->json()->setStatusCode(Response::HTTP_OK);
        }

        throw new Exception();
    }

    /**
     * Change profile.
     * 
     * @param ChangeProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeProfile(ChangeProfileRequest $request): JsonResponse
    {
        $user = $request->user('admin-api');

        $response = $this->sqlAdminRepository->update($user, $request->validated());

        if ($response instanceof Admin) {
            return response()->json()->setStatusCode(Response::HTTP_OK);
        }

        throw new Exception();
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin-api')->factory()->getTTL() * 60
        ]);
    }
}
