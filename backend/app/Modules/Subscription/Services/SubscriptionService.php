<?php

namespace App\Modules\Subscription\Services;

use App\Modules\Subscription\Repositories\SubscriptionRepository;
use App\Services\AbstractService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubscriptionService extends AbstractService
{
    private SubscriptionRepository $repositoryRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->repository = $subscriptionRepository;
        $this->repositoryRepository = $subscriptionRepository;
    }

    /**
     *  List subscriptions.
     *
     * @param  int|null|\Closure    $perPage
     * @return LengthAwarePaginator
     */
    public function getListSubscriptions(int $perPage): LengthAwarePaginator
    {
        return $this->repositoryRepository->listSubscriptions($perPage);
    }
}
