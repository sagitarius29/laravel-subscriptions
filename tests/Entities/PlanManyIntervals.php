<?php

namespace Sagitarius29\LaravelSubscriptions\Tests\Entities;

use Sagitarius29\LaravelSubscriptions\Plan;
use Sagitarius29\LaravelSubscriptions\Traits\HasManyIntervals;

class PlanManyIntervals extends Plan
{
    use HasManyIntervals;
}
