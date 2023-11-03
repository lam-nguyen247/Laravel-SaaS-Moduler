<?php

namespace App\Http\Controllers\SupperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Models\SupperAdmin;
use App\Services\SupperAdminService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateController extends Controller
{
    use ApiResponse;

    const AUTH_CODE = 'supper-admin';
    const LOGIN_VALIDATE = 'login';
    const CHANGE_PASSWORD_VALIDATE = 'change-password';
    const UPDATE_VALIDATE = 'update';

    private SupperAdminService $supperAdminService;

    public function __construct(SupperAdminService $supperAdminService)
    {
        $this->supperAdminService = $supperAdminService;
    }

    /**
     * Login by email and password
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), $this->validateRules(self::LOGIN_VALIDATE));
            if ($validator->fails()) {
                return $this->validateAccountResponse($validator);
            }
            if (! $token = auth(self::AUTH_CODE)->attempt($validator->validated())) {
                return $this->loginIncorrectApiResponse();
            }

            return $this->respondWithToken($token, self::AUTH_CODE);
        } catch (Exception $e) {
            return $this->errorApiResponse();
        }
    }

    /**
     * Get supper admin info
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(): JsonResponse
    {
        return $this->getSuccess(auth(self::AUTH_CODE)->user());
    }

    /**
     * Refresh token
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth(self::AUTH_CODE)->refresh(), self::AUTH_CODE);
    }

    /**
     * Logout
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $arr = [
                'messages' => 'Successfully logged out',
                'data' => []
            ];
            auth(self::AUTH_CODE)->logout();
            return response()->json($arr);
        } catch (Exception $e) {
            return $this->errorApiResponse();
        }
    }

    /**
     * Change password.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->validateRules(self::CHANGE_PASSWORD_VALIDATE));
            if ($validator->fails()) {
                return $this->validateAccountResponse($validator);
            }
            $userLogin = auth(self::AUTH_CODE)->user();
            $user = SupperAdmin::find($userLogin->id);
            if (!$this->supperAdminService->isComparePassword($input['old_password'], $user->password)) {
                return $this->passwordIncorrectApiResponse();
            }

            $user->fill([
                'password' => Hash::make($input['new_password'])
            ])->save();
            return $this->updateSuccess($user);
        } catch (Exception $e) {
            return $this->errorApiResponse();
        }
    }

    /**
     * Update supper admin info
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, $this->validateRules(self::UPDATE_VALIDATE));
            if ($validator->fails()) {
                return $this->validateAccountResponse($validator);
            }
            $userLogin = auth(self::AUTH_CODE)->user();
            $user = SupperAdmin::find($userLogin->id);

            $user->first_name = $input['first_name'];
            $user->last_name = $input['last_name'];
            $user->number_phone = $input['number_phone'];
            $user->address = $input['address'];
            $user->save();
            return $this->updateSuccess($user);
        } catch (Exception $e) {
            return $this->errorApiResponse();
        }
    }

    /**
     * Validate Rules
     * @param String $validateType
     * 
     * @return array
     */
    private function validateRules($validateType)
    {
        switch ($validateType) {
            case self::LOGIN_VALIDATE:
                return [
                    'email' => 'required',
                    'password' => 'required',
                ];
            case self::CHANGE_PASSWORD_VALIDATE:
                return [
                    'old_password' => 'required',
                    'new_password' => 'required',
                ];
            case self::UPDATE_VALIDATE:
                return [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'number_phone' => 'required',
                ];
        }

        return [];
    }
}
