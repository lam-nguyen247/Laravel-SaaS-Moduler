<?php

namespace App\Modules\SuperAdmin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SuperAdmin\Requests\AdminManagement\BlockAdminRequest;
use App\Modules\SuperAdmin\Services\AdminService;
use App\Traits\ResponseTrait;
use App\Modules\SuperAdmin\Requests\AdminManagement\CreateAdminRequest;
use App\Modules\SuperAdmin\Transformers\AdminTransformer;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class AdminManagementController extends Controller
{
    use ResponseTrait;

    public const AUTH_CODE = 'super-admin';

    private AdminService $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        parent::__construct();
    }

    /**
     * Create admin
     * @param CreateAdminRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createAdmin(CreateAdminRequest $request)
    {
        try {
            $data = $request->validated();

            return $this->successApiResponse('Create admin successful.', $this->adminService->StoreUser($data));
        } catch (\Throwable $e) {
            return $this->errorApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'Create admin failed.');
        }
    }

    /**
     * List all users by pagination
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAdmin(): JsonResponse
    {
        try {
            $result = $this->respondAllWithCollection(
                $this->adminService->getListAdmin(),
                new AdminTransformer(),
                'admins'
            );

            return $this->successApiResponse('Get list admin successfully.', $result->original['data']);
        } catch (\Throwable $e) {
            return $this->errorApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'Get list admin failed.');
        }
    }

    /**
     * Get user by id
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(int $id): JsonResponse
    {
        try {
            $admin = $this->adminService->findById($id);

            if (!$admin) {
                return $this->sendNotFoundResponse();
            }
            $result =  $this->respondWithItem(
                $admin,
                new AdminTransformer(),
                'admin'
            );

            return $this->successApiResponse('Get admin successfully.', $result->original['data']);
        } catch (\Throwable $e) {
            return $this->errorApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'Detail admin failed.');
        }
    }

    /**
     * Block admin
     * @param BlockAdminRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function block(BlockAdminRequest $request): JsonResponse
    {
        $admin_id = $request['admin_id'];

        /** @var bool $result */
        $result = $this->adminService->block($admin_id);

        if ($result) {
            return $this->successApiResponse('Block admin successful.', $result);
        }

        return $this->successApiResponse('Block admin failed.', $result);
    }

    /**
     * Unblock admin
     * @param BlockAdminRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblock(BlockAdminRequest $request): JsonResponse
    {
        $admin_id = $request['admin_id'];

        /** @var bool $result */
        $result = $this->adminService->unblock($admin_id);

        if ($result) {
            return $this->successApiResponse('Unblock admin successful.', $result);
        }

        return $this->successApiResponse('Unblock admin failed.', $result);
    }
}
