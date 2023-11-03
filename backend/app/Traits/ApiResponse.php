<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiResponse
 *
 * @package App\Traits
 */
trait ApiResponse
{
    /**
     * Error api response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorApiResponse(): JsonResponse
    {
        $arr = [
            'messages' => 'Call Api Failed',
            'data' => []
        ];
        return response()->json($arr, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Not found account response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFoundAccountResponse(): JsonResponse
    {
        $arr = [
            'messages' => 'Account Not Found',
            'data' => []
        ];
        return $arr;
        return response()->json($arr, Response::HTTP_NOT_FOUND);
    }

    /**
     * Validate account response
     * @param Request $validator
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateAccountResponse($validator): JsonResponse
    {
        $arr = [
            'message' => 'Validate Errors',
            'data' => $validator->errors()
        ];
        return response()->json($arr, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Login incorrect api response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginIncorrectApiResponse(): JsonResponse
    {
        $arr = [
            'messages' => 'Email or password is incorrect',
            'data' => []
        ];
        return response()->json($arr, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Password incorrect api response
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordIncorrectApiResponse(): JsonResponse
    {
        $arr = [
            'messages' => 'Old password does not match',
            'data' => []
        ];
        return response()->json($arr, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Get success response
     * @param Array $data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuccess($data): JsonResponse
    {
        $arr = [
            'messages' => 'Get successful',
            'data' => $data
        ];
        return response()->json($arr, Response::HTTP_OK);
    }

    /**
     * Create success response
     * @param Array $data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSuccess($data): JsonResponse
    {
        $arr = [
            'messages' => 'Create successful',
            'data' => $data
        ];
        return response()->json($arr, Response::HTTP_OK);
    }

    /**
     * Update success response
     * @param Array $data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSuccess($data): JsonResponse
    {
        $arr = [
            'messages' => 'Update successful',
            'data' => $data
        ];
        return response()->json($arr, Response::HTTP_OK);
    }

    /**
     * Delete success response
     * @param Array $data
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSuccess($data): JsonResponse
    {
        $arr = [
            'messages' => 'Delete successful',
            'data' => $data
        ];
        return response()->json($arr, Response::HTTP_OK);
    }

    /**
     * Respond with token
     * @param String $token
     * @param String $auth_code
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token, $auth_code): JsonResponse
    {
        $arr = [
            'messages' => 'Login successful',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth($auth_code)->factory()->getTTL() * 60 //mention the guard name inside the auth fn
            ]
        ];
        return response()->json($arr, Response::HTTP_OK);
    }
}