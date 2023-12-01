<?php

namespace App\Modules\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\User\Services\SubscriptionService;
use App\Modules\User\Transformers\HistorySubscriptionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
        parent::__construct();
    }

    /**
     * Get history subscription by userId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistory(): JsonResponse
    {
        try {
            $userId = auth('front-api')->user()->id;
            $history = $this->subscriptionService->history($userId);

            $result =  $this->respondAllWithCollection(
                $history,
                new HistorySubscriptionTransformer(),
                'user'
            );

            return $this->successApiResponse('Get subscription history successfully.', $result->original['data']);
        } catch (\Throwable $e) {
            return $this->errorApiResponse(Response::HTTP_INTERNAL_SERVER_ERROR, 'Get subscription history failed');
        }
    }
}
