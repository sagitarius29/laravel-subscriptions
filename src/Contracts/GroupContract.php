<?php


namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface GroupContract
{
    public function __construct($code = null);

    public function getCode(): string;

    public function plans();

    public function addPlan(PlanContract $plan): void;

    public function getDefaultPlan(): PlanContract;
}
