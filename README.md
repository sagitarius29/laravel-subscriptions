# Laravel Subscriptions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sagitarius29/laravel-subscriptions.svg?style=flat-square)](https://packagist.org/packages/sagitarius29/laravel-subscriptions)
[![StyleCI](https://github.styleci.io/repos/217646946/shield)](https://github.styleci.io/repos/217646946)
[![Build Status](https://img.shields.io/travis/sagitarius29/laravel-subscriptions/master.svg?style=flat-square)](https://travis-ci.org/sagitarius29/laravel-subscriptions)
[![Quality Score](https://img.shields.io/scrutinizer/g/sagitarius29/laravel-subscriptions.svg?style=flat-square)](https://scrutinizer-ci.com/g/sagitarius29/laravel-subscriptions)
[![Total Downloads](https://img.shields.io/packagist/dt/sagitarius29/laravel-subscriptions.svg?style=flat-square)](https://packagist.org/packages/sagitarius29/laravel-subscriptions)

A simple laravel package for Subscriptions

## Installation

You can install the package via composer:

```bash
composer require sagitarius29/laravel-subscriptions
```

#### Register Service Provider
Add `Sagitarius29\LaravelSubscriptions\LaravelSubscriptionsServiceProvider::class` to your file `config/app.php`

```php
'providers' => [
    /**
    * Some Providers
    */
    Sagitarius29\LaravelSubscriptions\LaravelSubscriptionsServiceProvider::class
]
```

#### Config file and migrations
Publish package config file and migrations with the following command:
```cmd
php artisan vendor:publish --provider="Sagitarius29\LaravelSubscriptions\LaravelSubscriptionsServiceProvider::class"
```

Then run migrations:
```cmd
php artisan migrate
```

## Features Overview

- Create plans and his features or consumables. (consumables is in development)
- Manage your plans: get all plans, disable, delete, etc.
- Your user can subscribe to a plan.
- The user can renew, cancel, upgrade or downgrade his subscription.
- Group your plans now is very simple.
- A lot more

## A few examples

### Configure your User model for use subscriptions
````php
<?php
use Sagitarius29\LaravelSubscriptions\Traits\HasSubscriptions;

class User extends Authenticable
{
    use HasSubscriptions; // Add this line for use subscriptions
````

### Create a plan with features

```php
<?php
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Entities\PlanFeature;
use Sagitarius29\LaravelSubscriptions\Entities\PlanInterval;

$plan = Plan::create(
        'name of plan',
        'this is a description',
        0,
        1
    );
$features = [
    PlanFeature::make('listings', 50, 1),
    PlanFeature::make('pictures_per_listing', 10, 1),
    PlanFeature::make('listing_duration_days', 30, 1),
    PlanFeature::make('listing_title_bold', true, 1),
];

// adding features to plan
$plan->features()->saveMany($features);

$plan->isFree(); // return true;

// adding interval of price
$interval = PlanInterval::make(PlanInterval::$MONTH, 1, 4.90);
$plan->setInterval($interval);

$plan->isFree(); // return false;
$plan->isNotFree(); // return true; 
```

### User is subscribing to a Plan
```php
<?php
use Sagitarius29\LaravelSubscriptions\Entities\Plan;

$user = \Auth::user();
$plan = Plan::find(1);

$user->subscribeTo($plan);

$user->hasActiveSubscription(); // return true;

$currentSubscription = $user->getActiveSubscription(); // return Subscription object;

```

## Upgrade or Downgrade Subscription
````php
<?php
use Sagitarius29\LaravelSubscriptions\Entities\Plan;

$user = \Auth::user();
$firstPlan = Plan::find(1);
$secondPlan = Plan::find(2);

//upgrade or downgrade depending of the price
$user->changePlanTo($secondPlan);
````

### User cancel subscription
````php
<?php
$user = \Auth::user();

// the subscription is will end in the expiration date
$user->cancelSubscription();

// the subscription end now
$user->forceCancelSubscription();
````

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ronnie.adolfo@gmail.com instead of using the issue tracker.

## Credits

- [Adolfo Cuadros](https://github.com/sagitarius29)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
