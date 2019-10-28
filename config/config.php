<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'entities' => [
        'plan'          => \Sagitarius29\LaravelSubscriptions\Entities\Plan::class,
        'plan_feature'  => \Sagitarius29\LaravelSubscriptions\Entities\PlanFeature::class,
        'plan_price'    => \Sagitarius29\LaravelSubscriptions\Entities\PlanPrice::class
    ]
];
