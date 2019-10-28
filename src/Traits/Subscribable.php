<?php


namespace Sagitarius29\LaravelSubscriptions\Traits;


use phpDocumentor\Reflection\Types\Boolean;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscribableContract;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;

trait Subscribable
{
    public function subscribeTo(Plan $plan)
    {

    }

    public function renewSubscription(): Boolean
    {

    }

    public function cancelSubscription(): Boolean
    {

    }

    public function getCurrentPlan(): Plan
    {

    }

    public function getConsumables()
    {

    }
}
