<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Enums;

enum SubscriptionModel: string
{
    case Payg = 'payg';
    case Fixed = 'fixed';

    /**
     * Get the display label for the subscription model.
     * Compatible with Filament's HasLabel interface if Filament is installed.
     */
    public function getLabel(): string
    {
        return __('subscription-plans::subscription-plans.subscription-model.'.$this->value);
    }
}
