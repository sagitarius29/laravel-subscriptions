<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\GroupContract;

class Group implements GroupContract
{
    protected $code = null;
    protected $modelPlan;

    public function __construct(string $code)
    {
        $this->code = $code;
        $this->modelPlan = config('subscriptions.entities.plan');
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function plans(): Builder
    {
        return $this->modelPlan::query()->byGroup($this);
    }

    public function addPlan(PlanContract $plan): void
    {
        $plan->changeToGroup($this);
    }

    public function addPlans(array $plans): void
    {
        foreach ($plans as $plan) {
            $this->addPlan($plan);
        }
    }

    public function hasPlans(): bool
    {
        return $this->plans()->count() > 0;
    }

    public function getDefaultPlan(): ?PlanContract
    {
        return $this->plans()->isDefault()->first();
    }

    public function __toString(): string
    {
        return $this->getCode();
    }
}
