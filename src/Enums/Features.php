<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Enums;

enum Features: string
{
    case Users = 'users';

    /**
     * Get the display label for the feature.
     * Compatible with Filament's HasLabel interface if Filament is installed.
     */
    public function getLabel(): string
    {
        return __('subscription-plans::subscription-plans.features.'.$this->value);
    }
}
