<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Repositories\Sql\sqlUserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private sqlUserRepository $sqlUserRepository;

    public function __construct(sqlUserRepository $sqlUserRepository)
    {
        $this->sqlUserRepository = $sqlUserRepository;
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
            $this->sqlUserRepository->getPaginate($filter['per_page']),
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
            $this->sqlUserRepository->save($data),
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
        $user = $this->getDataUserById($id);

        if (!$user instanceof User) {
            return response()->json(['message' => 'User not found'])->setStatusCode(Response::HTTP_BAD_REQUEST);
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
     * @param string $id
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $this->getDataUserById($id);

        if (!$user instanceof User) {
            return response()->json(['message' => 'User not found'])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $this->respondWithItem(
            $this->sqlUserRepository->update($user, $request->validated()),
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
        $user = $this->getDataUserById($id);

        if (!$user instanceof User) {
            return response()->json(['message' => 'User not found'])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $this->sqlUserRepository->delete($user);

        return $this->respondWithItem(
            $user,
            new UserTransformer(),
            'users'
        );
    }

    /**
     * Get data user by id
     * 
     * @param string $id
     * 
     * @return User | null
     */
    private function getDataUserById(string $id): User | null
    {
        return $this->sqlUserRepository->findOne($id);
    }
}
