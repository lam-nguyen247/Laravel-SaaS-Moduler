<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Requests\StoreUserRequest;
use App\Modules\Admin\Requests\UpdateUserRequest;
use App\Models\User;
use App\Modules\Admin\Services\UserService;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct();
    }

    /**
     * List all users by pagination
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filter = $request->only(['page', 'per_page']);

        return $this->respondWithCollection(
            $this->userService->getPaginate($filter['per_page']),
            new UserTransformer(),
            'users'
        );
    }

    /**
     * Create a new user
     *
     * @param StoreUserRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        return $this->respondWithItem(
            $this->userService->create($data),
            new UserTransformer(),
            'users'
        );
    }

    /**
     * Get user by id
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $user = $this->userService->show($id);

        if (!$user instanceof User) {
            return $this->errorApiResponse(Response::HTTP_BAD_REQUEST, 'User not found');
        }

        return $this->respondWithItem(
            $user,
            new UserTransformer(),
            'users'
        );
    }

    /**
     * Update user by id
     *
     * @param UpdateUserRequest $request
     * @param string            $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $this->userService->show($id);

        if (!$user instanceof User) {
            return $this->errorApiResponse(Response::HTTP_BAD_REQUEST, 'User not found');
        }

        return $this->respondWithItem(
            $this->userService->update($user, $request->validated()),
            new UserTransformer(),
            'users'
        );
    }

    /**
     * Remove user by id
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $user = $this->userService->show($id);

        if (!$user instanceof User) {
            return $this->errorApiResponse(Response::HTTP_BAD_REQUEST, 'User not found');
        }

        if ($this->userService->delete($user)) {
            return $this->respondWithItem(
                $user,
                new UserTransformer(),
                'users'
            );
        }

        return $this->respondWithItem(
            $user,
            new UserTransformer(),
            'users'
        );
    }
}
