<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Sagitarius29\LaravelSubscriptions\Contracts\PlanFeatureContract;

trait HasFeatures
{
    public function addFeature(PlanFeatureContract $feature)
    {
        $this->features()->save($feature);
    }

    public function addFeatures(array $features)
    {
        $this->features()->saveMany($features);
    }

    public function features()
    {
        return $this->hasMany(config('subscriptions.entities.plan_feature'));
    }
}
