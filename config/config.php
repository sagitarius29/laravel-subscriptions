<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'entities' => [
        'user'              => '\\App\\User',
        'plan'              => \Sagitarius29\LaravelSubscriptions\Entities\Plan::class,
        'plan_feature'      => \Sagitarius29\LaravelSubscriptions\Entities\PlanFeature::class,
        'plan_interval'     => \Sagitarius29\LaravelSubscriptions\Entities\PlanInterval::class,
        'plan_subscription' => \Sagitarius29\LaravelSubscriptions\Entities\Subscription::class,
    ],
];
