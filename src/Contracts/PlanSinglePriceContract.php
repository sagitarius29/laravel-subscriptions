<?php


namespace Sagitarius29\LaravelSubscriptions\Contracts;


interface PlanSinglePriceContract
{
    public function setPrice(PlanPriceContract $price): bool;

    public function getPrice(): ?PlanPriceContract;
}
