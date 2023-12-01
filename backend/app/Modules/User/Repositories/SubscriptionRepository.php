<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\Subscription;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\Collection;

class SubscriptionRepository extends AbstractRepository
{
    public function __construct(Subscription $subscription)
    {
        $this->model = $subscription;
        parent::__construct();
    }

    /**
     * Get history subscription by user_id
     *
     * @param  int        $user_id
     * @return Collection
     */
    public function history($user_id): Collection
    {
        $result = $this->model->query()->where('user_id', $user_id)->get();

        return $result;
    }
}
