<?php


namespace Sagitarius29\LaravelSubscriptions\Contracts;


use Illuminate\Database\Eloquent\Model;

interface PlanPriceContract
{
    public function plan();

    public function getAmount(): int;

    public function getInterval(): string;

    public function getIntervalUnit(): int;

    public static function make($interval, int $intervalUnit, float $amount): PlanPriceContract;

    public static function makeWithoutInterval(float $amount): PlanPriceContract;

}
