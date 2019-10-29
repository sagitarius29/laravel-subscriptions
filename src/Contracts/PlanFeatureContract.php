<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PlanFeatureContract
{
    public function plan();

    public function isConsumable(): bool;

    public function getValue(): int;

    public static function make(string $code, int $value, int $sortOrder, bool $isConsumable = false): Model;
}
