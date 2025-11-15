<?php

namespace NootPro\SubscriptionPlans\Events;

use Illuminate\Foundation\Events\Dispatchable;
use NootPro\SubscriptionPlans\Models\PlanSubscription;

class SubscriptionDeleted
{
    use Dispatchable;

    public function __construct(
        public PlanSubscription $subscription
    ) {}
}
