<?php

use Sagitarius29\LaravelSubscriptions\Entities\Plan;

$factory->define(Plan::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->word,
        'details'       => $faker->sentence,
        'free_days'     => $faker->randomNumber(2),
        'sys_active'    => $faker->boolean,
        'sys_default'   => 1
    ];
});
