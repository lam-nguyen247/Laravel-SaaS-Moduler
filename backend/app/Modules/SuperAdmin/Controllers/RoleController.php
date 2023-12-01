<?php

namespace App\Modules\SuperAdmin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\SuperAdmin\Requests\Role\PostRequest;
use App\Modules\SuperAdmin\Requests\Role\PutRequest;
use App\Modules\SuperAdmin\Services\RoleService;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    use ResponseTrait;

    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $roles = $this->roleService->all('permissions');

            return $this->successApiResponse('Get Successfully', $roles);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param PostRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostRequest $request): JsonResponse
    {
        try {
            $input = $request->all();
            $role = $this->roleService->createRole($input);

            return $this->successApiResponse('Create Successfully', $role);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Display the specified resource.
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $role = $this->roleService->findOne($id, ['permissions']);
            if (empty($role)) {
                return $this->sendNotFoundResponse();
            }

            return $this->successApiResponse('Get Successfully', $role);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param PutRequest $request
     * @param string     $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PutRequest $request, string $id): JsonResponse
    {
        try {
            $input = $request->all();
            $role = $this->roleService->findOne($id, ['permissions']);

            if (empty($role)) {
                return $this->sendNotFoundResponse();
            }

            if (!$input['isAddPermission'] && $this->roleService->checkRemoveAllPermissions($role, $input['permissions'])) {
                return $this->errorApiResponse(Response::HTTP_EXPECTATION_FAILED, 'Role must have at least one permission');
            }

            $this->roleService->updatePermissions($role, $input['permissions'], $input['isAddPermission']);

            return $this->successApiResponse('Update Successfully', $role);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $role = $this->roleService->findOne($id, ['permissions']);
            if (empty($role)) {
                return $this->sendNotFoundResponse();
            }
            $this->roleService->delete($role);

            return $this->successApiResponse('Delete Successfully', $role);
        } catch (Exception $e) {
            return $this->errorSystemApiResponse();
        }
    }
}
