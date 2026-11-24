<?php

declare(strict_types=1);

namespace NootPro\SubscriptionPlans\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use NootPro\SubscriptionPlans\Providers\SubscriptionPlansServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Sluggable\SluggableServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'NootPro\\SubscriptionPlans\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            SubscriptionPlansServiceProvider::class,
            SluggableServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
