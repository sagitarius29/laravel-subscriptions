<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

interface PlanIntervalContract
{
    const DAY = 'day';
    const MONTH = 'month';
    const YEAR = 'year';

    public static function make($type, int $unit, float $price): self;

    public static function makeInfinite(float $price): self;

    public function plan();

    public function getPrice(): float;

    public function getType(): string;

    public function getUnit(): int;

    public function isInfinite(): bool;

    public function isFree(): bool;

    public function isNotFree(): bool;
}
