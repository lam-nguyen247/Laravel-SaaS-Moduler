<?php

namespace App\Modules\Subscription\Controllers;

use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Modules\Subscription\Requests\StoreSubscriptionRequest;
use App\Modules\Subscription\Services\SubscriptionService;
use App\Modules\Subscription\Transformers\SubscriptionTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $SubscriptionService)
    {
        $this->subscriptionService = $SubscriptionService;

        parent::__construct();
    }

    /**
     * Create a new subscription.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $userId = auth('front-api')->user()->id;
        $data = $request->validated();

        return $this->respondWithItem(
            $this->subscriptionService->create(array_merge($data, ['status' => UserStatus::ACTIVE, 'user_id' => $userId])),
            new SubscriptionTransformer(),
            'subscriptions'
        );
    }

    /**
     * List subscriptions by pagination.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filter = $request->only(['page', 'per_page']);

        return $this->respondWithCollection(
            $this->subscriptionService->getListSubscriptions($filter['per_page']),
            new SubscriptionTransformer(),
            'subscriptions'
        );
    }
}
