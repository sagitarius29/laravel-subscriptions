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

    /**
     * @param  string  $code
     * @param  bool|int  $value
     * @param  int  $sortOrder
     * @param  bool  $isConsumable
     * @return Model
     */
    public static function make(
        string $code,
        $value,
        int $sortOrder,
        bool $isConsumable = null
    ): Model {
        $attributes = [
            'code' => $code,
            'value' => $value,
            'sort_order' => $sortOrder,
        ];

        if (is_bool($value)) {
            $attributes['is_consumable'] = false;
        } else {
            $attributes['is_consumable'] = true;
        }

        if ($isConsumable != null) {
            $attributes['is_consumable'] = $isConsumable;
        }

        return new self($attributes);
    }

    public function plan()
    {
        return $this->belongsTo(config('subscriptions.entities.plan'));
    }

    public function isConsumable(): bool
    {
        return $this->is_consumable;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
