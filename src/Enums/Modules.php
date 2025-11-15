<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Enums;

enum Modules: string
{
    case WebsiteContent = 'website_content';
    case Catalog = 'catalog';

    /**
     * Get the display label for the interval.
     * Compatible with Filament's HasLabel interface if Filament is installed.
     */
    public function getLabel(): string
    {
        return __('subscription-plans::subscription-plans.modules.'.$this->value);
    }
}
