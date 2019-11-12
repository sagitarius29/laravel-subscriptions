<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'entities' => [
        'user' => \App\User::class,
        'plan' => \Sagitarius29\LaravelSubscriptions\Entities\Plan::class,
        'plan_feature' => \Sagitarius29\LaravelSubscriptions\Entities\PlanFeature::class,
        'plan_interval' => \Sagitarius29\LaravelSubscriptions\Entities\PlanInterval::class,
        'plan_subscription' => \Sagitarius29\LaravelSubscriptions\Entities\Subscription::class,
    ],
    'default_features' => [
        'features' => [
            //'is_featured_clinic' => true
        ],
        'consumables' => [
            // Consumables
            //'number_of_contacts' => 5,
        ],
    ],
];
