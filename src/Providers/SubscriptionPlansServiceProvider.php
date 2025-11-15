<?php

namespace NootPro\SubscriptionPlans\Providers;

use Illuminate\Support\ServiceProvider;
use NootPro\SubscriptionPlans\Models\PlanSubscription;
use NootPro\SubscriptionPlans\Observers\PlanSubscriptionObserver;

class SubscriptionPlansServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../../config/subscription-plans.php',
            'subscription-plans'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../../lang', 'subscription-plans');

        // Publish migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../database/migrations' => database_path('migrations'),
            ], 'subscription-plans-migrations');

            // Publish config
            $this->publishes([
                __DIR__.'/../../config/subscription-plans.php' => config_path('subscription-plans.php'),
            ], 'subscription-plans-config');

            // Publish translations
            $this->publishes([
                __DIR__.'/../../lang' => $this->app->langPath('vendor/subscription-plans'),
            ], 'subscription-plans-translations');
        }

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Register observers
        PlanSubscription::observe(PlanSubscriptionObserver::class);
    }
}
