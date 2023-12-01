<?php

namespace App\Modules\Subscription\Transformers;

use App\Modules\Subscription\Models\Subscription;
use App\Transformers\AbstractTransformer;

class SubscriptionTransformer extends AbstractTransformer
{
    public function transform(Subscription $subscription): array
    {
        return [
            'id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'subscription_reference' => $subscription->subscription_reference,
            'trial_ends_at' => $subscription->trial_ends_at,
            'next_billing_at' => $subscription->next_billing_at,
            'status' => $subscription->status,
            'gateway' => $subscription->gateway,
            'extras' => $subscription->extras,
            'properties' => $subscription->properties,
            'created_by' => $subscription->created_by,
            'updated_by' => $subscription->updated_by,
            'created_at' => $subscription->created_at,
            'updated_at' => $subscription->updated_at,
            'deleted_at' => $subscription->deleted_at,
        ];
    }
}
