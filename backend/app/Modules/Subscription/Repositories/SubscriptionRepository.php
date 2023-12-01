<?php

namespace App\Modules\Subscription\Repositories;

use App\Modules\Subscription\Models\Subscription;
use App\Repositories\AbstractRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SubscriptionRepository extends AbstractRepository
{
    public function __construct(Subscription $subscription)
    {
        $this->model = $subscription;

        parent::__construct();
    }

    /**
     * Paginate the given query list subscriptions.
     *
     * @param  int|null|\Closure    $perPage
     * @return LengthAwarePaginator
     */
    public function listSubscriptions(int|null|\Closure $perPage): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }
}
