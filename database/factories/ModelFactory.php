<?php

use Sagitarius29\LaravelSubscriptions\Entities\Plan;

$factory->define(\Sagitarius29\LaravelSubscriptions\Tests\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Plan::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->word,
        'details'       => $faker->sentence,
        'free_days'     => $faker->randomNumber(2),
        'sys_active'    => $faker->boolean,
        'sys_default'   => 1
    ];
});
