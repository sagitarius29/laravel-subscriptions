<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sagitarius29\LaravelSubscriptions\PlanFeature;

trait HasFeatures
{
    /**
     * @param  PlanFeature|Model  $feature
     */
    public function addFeature(PlanFeature $feature)
    {
        $this->features()->save($feature);
    }

    public function features(): HasMany
    {
        return $this->hasMany(config('subscriptions.entities.plan_feature'));
    }

    public function addFeatures(array $features)
    {
        $this->features()->saveMany($features);
    }

    public function getFeatureByCode($featureCode)
    {
        return $this->features()->whereCode($featureCode)->first();
    }

    public function hasFeatures(): bool
    {
        return $this->features()->exists();
    }
}
