<?php

namespace App\Modules\User\Controllers;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\User\Requests\ChangePasswordRequest;
use App\Modules\User\Requests\LoginRequest;
use App\Modules\User\Requests\StoreUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Services\UserService;
use App\Transformers\UserTransformer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class AuthenticateController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        parent::__construct();
    }

    /**
     * Login
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = $this->userService->verifyLogin($credentials)) {
            return $this->errorApiResponse(Response::HTTP_UNAUTHORIZED, 'User not found');
        }

        return $this->respondWithToken($token, 'front-api');
    }

    /**
     * Create a new user
     *
     * @param StoreUserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(StoreUserRequest $request)
    {
        $data = $request->validated();

        return $this->respondWithItem(
            $this->userService->create(array_merge($data, ['status' => UserStatus::ACTIVE])),
            new UserTransformer(),
            'users'
        );
    }

    /**
     * Change password.
     *
     * @param ChangePasswordRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = $request->user('front-api');

        if (!$this->userService->changePassword($user, $credentials)) {
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
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeProfile(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user('front-api');

        $response = $this->userService->update($user, $request->validated());

        if ($response instanceof User) {
            return $this->getSuccess($response);
        }

        throw new Exception();
    }

    /**
     * forgot password.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $result = $this->userService->forgotPassword($request->get('email'));

        if (!$result) {
            return response()->json([
                'message' => 'The email field is incorrect',
                'errors' => [
                    'email' => ['The email field is incorrect'],
                ],

            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->getSuccess();
    }

    /**
     * reset password.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->only(['token', 'email', 'password']);

        $result = $this->userService->resetPassword($data['email'], $data['token'], $data['password']);

        if (!$result) {
            return response()->json([
                'message' => 'This password reset token is invalid',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->getSuccess();
    }

    /**
     * Get user info
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->successApiResponse('Get successful', auth('front-api')->user());
    }

    /**
     * Redirect social by provider.
     *
     * @param string $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToProvider(string $provider): JsonResponse
    {
        return response()->json([
            'redirectUrl' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function providerCallback(string $provider)
    {
        try {
            $token = $this->userService->handleCallbackSocial($provider);

            if (is_null($token)) {
                return $this->errorApiResponse(400, 'Account is block');
            }

            return $this->respondWithToken($token, 'front-api');
        } catch (Exception $e) {
            return $this->errorApiResponse(400, 'Cannot handle callback');
        }
    }
}
