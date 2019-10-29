<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Sagitarius29\LaravelSubscriptions\Contracts\PlanPriceContract;

trait HasManyPrices
{
    public function setPrices(array $prices)
    {
        $this->prices()->delete();
        $this->prices()->saveMany($prices);
    }

    public function addPrice(PlanPriceContract $price)
    {
        $this->prices()->save($price);
    }
}
