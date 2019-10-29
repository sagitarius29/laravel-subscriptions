<?php

namespace Sagitarius29\LaravelSubscriptions\Tests\Entities;

use Sagitarius29\LaravelSubscriptions\Plan;
use Sagitarius29\LaravelSubscriptions\Traits\HasManyPrices;

class PlanManyPrices extends Plan
{
    use HasManyPrices;
}
