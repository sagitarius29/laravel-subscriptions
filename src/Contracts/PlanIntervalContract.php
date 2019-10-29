<?php


namespace Sagitarius29\LaravelSubscriptions\Contracts;


interface PlanIntervalContract
{
    public function plan();

    public function getPrice(): int;

    public function getType(): string;

    public function getUnit(): int;

    public function isInfinite(): bool;

    public static function make($type, int $unit, float $price): self;

    public static function makeInfinite(float $price): self;
}
