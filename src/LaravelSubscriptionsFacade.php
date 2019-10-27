<?php

namespace Sagitarius29\LaravelSubscriptions;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Sagitarius29\LaravelSubscriptions\Skeleton\SkeletonClass
 */
class LaravelSubscriptionsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-subscriptions';
    }
}
