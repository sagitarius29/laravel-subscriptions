<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\PlanFeature as PlanFeatureBase;

class PlanConsumable extends PlanFeatureBase
{
    protected $attributes = [
        'is_consumable' => true
    ];

    public static function make(
        string $code,
        int $value,
        int $sortOrder = null
    ): Model {
        $attributes = [
            'code' => $code,
            'value' => $value,
            'sort_order' => $sortOrder,
        ];

        return new self($attributes);
    }
}
