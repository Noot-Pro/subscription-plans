<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Observers;

use NootPro\SubscriptionPlans\Models\PlanModule;

/**
 * PlanModuleObserver
 * 
 * Observer for PlanModule model lifecycle events.
 */
class PlanModuleObserver
{
    /**
     * Handle the module "created" event.
     */
    public function created(PlanModule $module): void
    {
        // Add any logic needed when a module is created
    }

    /**
     * Handle the module "updated" event.
     */
    public function updated(PlanModule $module): void
    {
        // Add any logic needed when a module is updated
    }

    /**
     * Handle the module "deleted" event.
     */
    public function deleted(PlanModule $module): void
    {
        // Add any logic needed when a module is deleted
    }

    /**
     * Handle the module "restored" event.
     */
    public function restored(PlanModule $module): void
    {
        // Add any logic needed when a module is restored
    }
}

