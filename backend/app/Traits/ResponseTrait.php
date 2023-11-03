<?php

namespace App\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\TransformerAbstract;

trait ResponseTrait
{
    /**
     * Status code of response
     *
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * Fractal manager instance
     *
     * @var Manager
     */
    protected $fractal;

    /**
     * Getter for statusCode
     *
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param int $statusCode Value to set
     *
     * @return self
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Send custom data response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCustomResponse(int $status, string $message)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }

    /**
     * Send generic error response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendErrorResponse(string $message, int $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(['status' => $code, 'message' => $message, 'error_code' => $code], $code);
    }

    /**
     * Send this response when api user provide fields that doesn't exist in our application
     *
     * @return mixed
     */
    public function sendUnknownFieldResponse($errors)
    {
        return response()->json((['status' => Response::HTTP_BAD_REQUEST, 'unknown_fields' => $errors]), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send this response when api user provide filter that doesn't exist in our application
     *
     * @return mixed
     */
    public function sendInvalidFilterResponse($errors)
    {
        return response()->json((['status' => Response::HTTP_BAD_REQUEST, 'invalid_filters' => $errors]), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send this response when api user provide incorrect data type for the field
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvalidFieldResponse($errors)
    {
        return response()->json((['status' => Response::HTTP_BAD_REQUEST, 'invalid_fields' => $errors]), Response::HTTP_BAD_REQUEST);
    }

    /**
     * Send this response when a api user try access a resource that they don't belong
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendForbiddenResponse()
    {
        return response()->json(['status' => Response::HTTP_FORBIDDEN, 'message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
    }

    /**
     * Send 404 not found response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendNotFoundResponse(string $message = '')
    {
        if ($message === '') {
            $message = 'The requested resource was not found';
        }

        return response()->json(['status' => Response::HTTP_NOT_FOUND, 'message' => $message], Response::HTTP_NOT_FOUND);
    }

    /**
     * Send empty data response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendEmptyDataResponse()
    {
        return $this->respondWithArray(['data' => new \StdClass()]);
    }

    /**
     * Set fractal Manager instance
     */
    protected function setFractal(Manager $fractal)
    {
        $request = app('request');
        $include = $request->query('include');
        $exclude = $request->query('exclude');
        $fields = $request->query('fields');

        if ($include) {
            $fractal->parseIncludes($include);
        }

        if ($exclude) {
            $fractal->parseExcludes($exclude);
        }

        if ($fields) {
            $fractal->parseFieldsets($fields);
        }
        $fractal->setSerializer(new DataArraySerializer());
        $this->fractal = $fractal;
    }

    /**
     * @param array|\Illuminate\Database\Eloquent\Collection|LengthAwarePaginator $collection
     * @param \Closure|TransformerAbstract                                        $callback
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback, string $resourceKey)
    {
        if (method_exists($callback, 'collection')) {
            $resource = $callback->collection($collection, $callback, $resourceKey);
        } else {
            $resource = new Collection($collection, $callback, $resourceKey);
        }

        // set empty data pagination
        if (empty($collection)) {
            $collection = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $resource = new Collection($collection, $callback, $resourceKey);
        }
        $resource->setPaginator(new IlluminatePaginatorAdapter($collection));
        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Return collection response from the application
     *
     * @param array|\Generator|\Illuminate\Support\Collection $collection
     * @param \Closure|TransformerAbstract                    $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondAllWithCollection($collection, $callback, string $resourceKey)
    {
        $resource = new Collection($collection, $callback, $resourceKey);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Return single item response from the application
     *
     * @param array|Model                  $item
     * @param \Closure|TransformerAbstract $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback, string $resourceKey)
    {
        $resource = new Item($item, $callback, $resourceKey);
        $rootScope = $this->fractal->createData($resource);
        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Return a json response from the application
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array, array $headers = [])
    {
        return response()->json($array, $this->statusCode, $headers);
    }
}
