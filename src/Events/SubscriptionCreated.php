<?php

namespace NootPro\SubscriptionPlans\Events;

use Illuminate\Foundation\Events\Dispatchable;
use NootPro\SubscriptionPlans\Models\PlanSubscription;

class SubscriptionCreated
{
    use Dispatchable;

    public function __construct(
        public PlanSubscription $subscription
    ) {}
}
