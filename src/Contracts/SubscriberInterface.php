<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use NootPro\SubscriptionPlans\Models\Plan;
use NootPro\SubscriptionPlans\Models\PlanSubscription;

/**
 * Interface SubscriberInterface
 * 
 * This interface should be implemented by any model that can have subscriptions.
 * Example: User, Company, Organization, etc.
 */
interface SubscriberInterface
{
    /**
     * Get all plan subscriptions for this subscriber.
     *
     * @return MorphMany<PlanSubscription, $this>
     */
    public function planSubscriptions(): MorphMany;

    /**
     * Get active plan subscriptions.
     *
     * @return Collection<int, PlanSubscription>
     */
    public function activePlanSubscriptions(): Collection;

    /**
     * Get a specific subscription by slug.
     */
    public function planSubscription(string $subscriptionSlug): ?PlanSubscription;

    /**
     * Get all plans this subscriber is subscribed to.
     *
     * @return Collection<int, Plan>
     */
    public function subscribedPlans(): Collection;

    /**
     * Check if subscriber is subscribed to a specific plan.
     */
    public function subscribedTo(int $planId): bool;

    /**
     * Create a new subscription to a plan.
     */
    public function newPlanSubscription(string $subscription, Plan $plan, ?Carbon $startDate = null): PlanSubscription;

    /**
     * Get the currently active subscription.
     */
    public function activePlanSubscription(): ?PlanSubscription;
}

