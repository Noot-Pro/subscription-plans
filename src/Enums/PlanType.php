<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Enums;

enum PlanType: string
{
    case Plan = 'plan';
    case TestPlan = 'test_plan';

    /**
     * Get the display label for the plan type.
     * Compatible with Filament's HasLabel interface if Filament is installed.
     */
    public function getLabel(): string
    {
        return __('subscription-plans::subscription-plans.plan-type.'.$this->value);
    }
}
