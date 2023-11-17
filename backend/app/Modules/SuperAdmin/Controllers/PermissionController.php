<?php

namespace App\Modules\SuperAdmin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SuperAdmin\Services\PermissionService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ResponseTrait;

    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $permission = $this->permissionService->all();

            return $this->successApiResponse('Get Successfully', $permission);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Get permissions without
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPermissionWithout(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            $permissions = $this->permissionService->findBy(
                [
                    'except' => [
                        'id' => $input['permissions'] ?? [],
                    ],
                ],
                null,
                false
            );

            return $this->successApiResponse('Get Successfully', $permissions);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }
}
