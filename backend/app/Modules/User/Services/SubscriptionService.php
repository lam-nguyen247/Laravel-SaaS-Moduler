<?php

namespace App\Modules\User\Services;

use App\Services\AbstractService;
use App\Modules\User\Repositories\SubscriptionRepository;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionService extends AbstractService
{
    private SubscriptionRepository $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Get history subscription by userId
     *
     * @param  int        $userId
     * @return Collection
     */
    public function history(int $userId): Collection
    {
        return $this->subscriptionRepository->history($userId);
    }
}
