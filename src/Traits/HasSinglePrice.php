<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Sagitarius29\LaravelSubscriptions\Contracts\PlanPriceContract;

trait HasSinglePrice
{
    public function setPrice(PlanPriceContract $price): void
    {
        $priceLoaded = $this->getPrice();

        if ($priceLoaded == null) {
            $this->prices()->save($price);

            return;
        }

        $priceLoaded->fill($price->toArray());
        $priceLoaded->save();
    }

    public function getPrice(): ?PlanPriceContract
    {
        return $this->prices()->first();
    }
}
