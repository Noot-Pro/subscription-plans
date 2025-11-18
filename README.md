# Subscription Plans
A comprehensive, flexible, and production-ready subscription and plans management system for Laravel applications. Perfect for SaaS applications, membership sites, and any project requiring subscription-based access control.

## Features

### Core Features
- ✅ Multiple subscription plans with pricing
- ✅ Feature-based limits (unlimited, disabled, or numeric)
- ✅ Usage tracking with automatic resets
- ✅ Trial periods
- ✅ Grace periods
- ✅ Plan upgrades/downgrades with proration
- ✅ Multilingual support (Arabic, English)
- ✅ Polymorphic subscribers (Company, User, etc.)
- ✅ Soft deletes for history
- ✅ Event-driven architecture
- ✅ Module-based access control

### Advanced Features
- ✅ **Subscription Middleware** - Built-in `EnsureSubscriptionValid` middleware to protect routes with subscription validation
- ✅ **ModulesGate Trait** - Authorization for Filament resources/pages based on active subscription modules

## Installation

### Via Composer

```bash
composer require noot-web/subscription-plans
```

### Publish Migrations

```bash
php artisan vendor:publish --tag=subscription-plans-migrations
php artisan migrate
```

### Publish Config

```bash
php artisan vendor:publish --tag=subscription-plans-config
```

### Publish Translations

```bash
php artisan vendor:publish --tag=subscription-plans-translations
```

## Quick Start

### 1. Add Trait to Your Model

Add the `HasPlanSubscriptions` trait to your subscriber model (e.g., User, Company):

```php
use NootPro\SubscriptionPlans\Traits\HasPlanSubscriptions;

class Company extends Model
{
    use HasPlanSubscriptions;
}
```

### 2. Create a Plan

```php
use NootPro\SubscriptionPlans\Models\Plan;
use NootPro\SubscriptionPlans\Enums\SubscriptionModel;
use NootPro\SubscriptionPlans\Enums\PlanType;
use NootPro\SubscriptionPlans\Enums\Interval;

$plan = Plan::create([
    'name' => ['en' => 'Pro Plan'],
    'slug' => 'pro-plan',
    'price' => 99.00,
    'currency' => 'USD',
    'invoice_period' => 1,
    'invoice_interval' => Interval::Month,
    'trial_period' => 14,
    'trial_interval' => Interval::Day,
    'subscription_model' => SubscriptionModel::Fixed,
    'type' => PlanType::Plan,
    'is_active' => true,
    'is_visible' => true,
]);
```

### 3. Add Features to Plan

```php
use NootPro\SubscriptionPlans\Enums\Features;
use NootPro\SubscriptionPlans\Enums\Interval;

$plan->features()->create([
    'slug' => Features::Users->value,
    'name' => ['en' => 'Users'],
    'value' => 10, // 10 users allowed
    'resettable_period' => 0, // No reset
    'resettable_interval' => Interval::Month,
    'sort_order' => 1,
]);
```

### 4. Create Subscription

```php
$company = Company::find(1);
$plan = Plan::where('slug', 'pro-plan')->first();

$subscription = $company->newPlanSubscription($plan);
```

### 5. Track Usage

```php
// Record usage
$subscription->recordFeatureUsage(Features::Users->value, 1);

// Check if can use
if ($subscription->canUseFeature(Features::Users->value)) {
    // Create user
}

// Get remaining
$remaining = $subscription->getFeatureRemainings(Features::Users->value);
```

## Events

The package fires events for subscription lifecycle:

- `SubscriptionCreated` - When a subscription is created
- `SubscriptionUpdated` - When a subscription is updated
- `SubscriptionDeleted` - When a subscription is deleted
- `SubscriptionRestored` - When a subscription is restored

Listen to events in your `EventServiceProvider`:

```php
use NootPro\SubscriptionPlans\Events\SubscriptionCreated;

protected $listen = [
    SubscriptionCreated::class => [
        // Your listeners
    ],
];
```

## Usage Examples

### Check Subscription Status

```php
$subscription->active();   // bool
$subscription->onTrial();  // bool
$subscription->canceled(); // bool
$subscription->ended();    // bool
```

### Cancel Subscription

```php
$subscription->cancel(); // Cancel at end of period
$subscription->cancel(immediately: true); // Cancel immediately
```

### Renew Subscription

```php
$subscription->renew();
```

### Change Plan

```php
$newPlan = Plan::where('slug', 'enterprise-plan')->first();
$subscription->changePlan($newPlan);
```

### Query Subscriptions

```php
use NootPro\SubscriptionPlans\Models\PlanSubscription;

// Get active subscription
$subscription = $company->activePlanSubscription();

// Get all active subscriptions
$activeSubscriptions = $company->activePlanSubscriptions();

// Check if subscribed to a plan
if ($company->subscribedTo($planId)) {
    // User has this plan
}

// Find subscriptions ending soon
PlanSubscription::findEndingPeriod(7)->get(); // Ending in 7 days
PlanSubscription::findEndingTrial(3)->get(); // Trial ending in 3 days
```

## Configuration

Edit `config/subscription-plans.php` to customize:

- Tax rate
- Model classes
- Enum classes
- Table names
- Feature behavior

## Requirements

- PHP >= 8.2
- Laravel >= 11.0
- MySQL 5.7+ / PostgreSQL 9.6+ / SQLite 3.8+

## Best Practices

This package follows Laravel and PHP best practices:

- ✅ PSR-12 Coding Standards
- ✅ Type Safety with strict types
- ✅ Mass Assignment Protection
- ✅ Database Transactions
- ✅ Event-Driven Architecture
- ✅ Comprehensive Testing (PEST)
- ✅ Soft Deletes for data integrity
- ✅ Polymorphic Relationships
- ✅ Query Scopes

## Advanced Usage

### Custom Subscriber Models

Any model can be a subscriber by using the trait:

```php
use NootPro\SubscriptionPlans\Traits\HasPlanSubscriptions;

class Team extends Model
{
    use HasPlanSubscriptions;
}
```

### Feature Limits

Features support three types of limits:

1. **Numeric** - Limited quantity (e.g., 10 users)
2. **Unlimited** - Value of -1 means no limit
3. **Disabled** - Value of 0 means feature is disabled

### Usage Reset Periods

Features can automatically reset usage after a period:

```php
$feature->update([
    'resettable_period' => 1,
    'resettable_interval' => 'month', // day, week, month, year
]);
```

### Proration Support

The package includes proration fields for handling mid-period plan changes:

- `prorate_day` - Day of month to prorate
- `prorate_period` - Proration period
- `prorate_extend_due` - Extend due date after proration

### Module-Based Access Control with ModulesGate

The `ModulesGate` trait provides automatic authorization for Filament resources and pages based on active subscription modules. It checks if the current tenant/company has the required modules enabled before allowing access.

#### Basic Usage

Add the trait to your Filament resource or page:

```php
use NootPro\SubscriptionPlans\Traits\ModulesGate;
use NootPro\SubscriptionPlans\Enums\Modules;
use Filament\Resources\Resource;

class WebsiteResource extends Resource
{
    use ModulesGate;

    /**
     * Define which modules are required for this resource.
     * Can be a single module string or an array of modules.
     */
    protected static function getModuleNames(): array|string
    {
        return Modules::WebsiteContent->value;
    }
}
```

#### Multiple Modules

You can require multiple modules (access granted if any module is active):

```php
protected static function getModuleNames(): array|string
{
    return [
        Modules::WebsiteContent->value,
        Modules::Blog->value,
    ];
}
```

#### Custom Tenant Model

If your tenant model is different from the default, override the method:

```php
protected static function getTenantModelClass(): ?string
{
    return \App\Models\Organization::class;
}
```

#### Available Authorization Methods

The trait automatically provides these Filament authorization methods:

- `shouldRegisterNavigation()` - Controls navigation visibility
- `canViewAny()` - Controls list view access
- `canAccess()` - Controls page/resource access
- `canCreate()` - Controls create access
- `canUpdate()` - Controls update access
- `canDelete()` - Controls delete access
- `canDeleteAny()` - Controls bulk delete access
- `canRestore()` - Controls restore access
- `canForceDelete()` - Controls force delete access
- `canView()` - Controls individual record view access

All methods check if any of the required modules are active for the current tenant.

#### How It Works

1. The trait gets the current tenant from Filament context or user relationships
2. It checks if the tenant has any of the required modules enabled via `SubscriptionPlans::moduleEnabled()`
3. Module status is cached for performance
4. If no modules are active, access is denied

#### Configuration

Make sure your `config/subscription-plans.php` has the tenant model configured:

```php
'tenant_model' => \App\Models\Company::class,
```

Or the trait will attempt to auto-detect common tenant model names.

### Subscription Middleware

The package includes a built-in middleware `EnsureSubscriptionValid` to protect routes:

```php
// Register in bootstrap/app.php (Laravel 11+)
use NootPro\SubscriptionPlans\Http\Middleware\EnsureSubscriptionValid;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'subscription' => EnsureSubscriptionValid::class,
    ]);
})

// Use in routes
Route::middleware(['auth', 'subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

The middleware automatically:
- Resolves the subscriber from the request (via config or auto-detection)
- Checks for active subscription (with caching for performance)
- Redirects to a configurable route if no active subscription exists

Configure the redirect route in `config/subscription-plans.php`:

```php
'middleware' => [
    'redirect_route' => env('SUBSCRIPTION_REDIRECT_ROUTE', 'subscription.plans'),
],
```

## Testing

This package uses **PEST** for testing - a delightful testing framework with a focus on simplicity.

Run the test suite:

```bash
composer test
```

Generate coverage report:

```bash
composer test-coverage
```

Run with profiling:

```bash
composer test-profile
```

Or use PEST directly:

```bash
vendor/bin/pest
vendor/bin/pest --parallel
vendor/bin/pest --coverage --min=80
```

## Security

If you discover any security issues, please email support@noot-web.com instead of using the issue tracker.

## Documentation

- **[README.md](README.md)** - Main documentation (you are here)
- **[IMPLEMENTATION_GUIDE.md](IMPLEMENTATION_GUIDE.md)** - Step-by-step implementation guide
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Contribution guidelines
- **[CHANGELOG.md](CHANGELOG.md)** - Version history

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute.

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.

## Credits

- [Hamza Mughales](https://github.com/Hamza-Mughales)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support

- 📧 Email: support@noot-web.com
- 🐛 Issues: [GitHub Issues](https://github.com/noot-web/subscription-plans/issues)
- 💬 Discussions: [GitHub Discussions](https://github.com/noot-web/subscription-plans/discussions)
- 📖 Documentation: [Full Documentation](https://docs.noot-web.com)

