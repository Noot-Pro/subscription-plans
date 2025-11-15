<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Enums;

enum Interval: string
{
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';

    /**
     * Get the display label for the interval.
     * Compatible with Filament's HasLabel interface if Filament is installed.
     */
    public function getLabel(): string
    {
        return __('subscription-plans::subscription-plans.interval.'.$this->value);
    }
}
