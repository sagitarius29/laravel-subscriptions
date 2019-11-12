<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PlanFeatureContract
{
    /**
     * @param  string  $code
     * @param  int|bool  $value
     * @param  int  $sortOrder
     * @param  bool  $isConsumable
     * @return Model
     */
    public static function make(
        string $code,
        $value,
        int $sortOrder = null
    ): Model;

    public function plan();

    public function isConsumable(): bool;

    public function getCode(): string;

    public function getValue();
}
