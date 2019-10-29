<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanFeatureContract;

class PlanFeature extends Model implements PlanFeatureContract
{
    protected $table = 'plan_features';

    protected $fillable = [
        'code', 'value', 'sort_order', 'is_consumable',
    ];

    public function plan()
    {
        return $this->belongsTo(config('subscription.entities.plan'));
    }

    public function isConsumable(): bool
    {
        return $this->is_consumable;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public static function make(string $code, int $value, int $sortOrder, bool $isConsumable = false): Model
    {
        $attributes = [
            'code'          => $code,
            'value'         => $value,
            'sort_order'    => $sortOrder,
            'is_consumable' => $isConsumable,
        ];

        return new self($attributes);
    }
}
