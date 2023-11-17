<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Admin\Models\Upload;
use App\Modules\Admin\Requests\StoreUploadRequest;
use App\Modules\Admin\Requests\UpdateUploadRequest;
use App\Modules\Admin\Services\UploadService;
use App\Modules\Admin\Transformers\UploadTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UploadController extends Controller
{
    private UploadService $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;

        parent::__construct();
    }

    /**
     * List setting upload
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->respondAllWithCollection(
            $this->uploadService->findBy(),
            new UploadTransformer(),
            'uploads'
        );
    }

    /**
     * Create a new upload
     *
     * @param StoreUploadRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUploadRequest $request)
    {
        $data = $request->validated();

        return $this->respondWithItem(
            $this->uploadService->create($data),
            new UploadTransformer(),
            'uploads'
        );
    }

    /**
     * Get upload by id
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $upload = $this->uploadService->show($id);

        if (!$upload instanceof Upload) {
            return response()->json(['message' => 'Not found'])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return $this->respondWithItem(
            $upload,
            new UploadTransformer(),
            'uploads'
        );
    }

    /**
     * Update upload by id
     *
     * @param UpdateUploadRequest $request
     * @param string              $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUploadRequest $request, string $id): JsonResponse
    {
        $upload = $this->uploadService->show($id);

        if (!$upload instanceof Upload) {
            return $this->errorApiResponse(Response::HTTP_BAD_REQUEST, 'Not found');
        }

        return $this->respondWithItem(
            $this->uploadService->update($upload, $request->validated()),
            new UploadTransformer(),
            'uploads'
        );
    }

    /**
     * Remove upload by id
     *
     * @param string $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $upload = $this->uploadService->show($id);

        if (!$upload instanceof Upload) {
            return $this->errorApiResponse(Response::HTTP_BAD_REQUEST, 'Not found');
        }

        $this->uploadService->delete($upload);

        return $this->respondWithItem(
            $upload,
            new UploadTransformer(),
            'uploads'
        );
    }
}
