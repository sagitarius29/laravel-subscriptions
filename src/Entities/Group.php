<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\GroupContract;

class Group implements GroupContract
{
    public function __construct($code = null)
    {
    }

    public function getCode(): string
    {
        // TODO: Implement getCode() method.
    }

    public function plans()
    {
        // TODO: Implement plans() method.
    }

    public function addPlan(PlanContract $plan): void
    {
        // TODO: Implement addPlan() method.
    }

    public function getDefaultPlan(): PlanContract
    {
        // TODO: Implement getDefaultPlan() method.
    }
}
