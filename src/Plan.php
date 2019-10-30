<?php

namespace Sagitarius29\LaravelSubscriptions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\GroupContract;

abstract class Plan extends Model implements PlanContract
{
    protected $table = 'plans';

    protected $fillable = [
        'name', 'description', 'free_days', 'sort_order', 'is_active', 'is_default', 'group',
    ];

    public function scopeByGroup(Builder $q, GroupContract $group = null)
    {
        return $q->where('group', $group);
    }

    public function scopeIsDefault(Builder $q)
    {
        return $q->where('is_default', 1);
    }

    public function features()
    {
        return $this->hasMany(config('subscriptions.entities.plan_feature'));
    }

    public function intervals()
    {
        return $this->hasMany(config('subscriptions.entities.plan_interval'), 'plan_id')
            ->orderBy('price');
    }

    public function subscriptions()
    {
        return $this->hasMany(config('subscriptions.entities.plan_subscription'));
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isFree(): bool
    {
        return $this->intervals()->count() == 0 || $this->intervals()->first()->price == 0;
    }

    public function isNotFree(): bool
    {
        return $this->intervals()->count() > 0 && $this->intervals()->first()->price > 0;
    }

    public function hasManyIntervals(): bool
    {
        return $this->intervals()->count() > 1;
    }

    public static function create(
        string $name,
        string $description,
        int $free_days,
        int $sort_order,
        bool $is_active = false,
        bool $is_default = false,
        GroupContract $group = null
    ): Model {
        $attributes = [
            'name'          => $name,
            'description'   => $description,
            'free_days'     => $free_days,
            'sort_order'    => $sort_order,
            'is_active'     => $is_active,
            'is_default'    => $is_default,
            'group'         => $group,
        ];
        $calledClass = get_called_class();

        if (! self::defaultExists($group)) {
           $attributes['is_default'] = true;
        }

        $plan = new $calledClass($attributes);
        $plan->save();

        return $plan;
    }

    public function setFree()
    {
        $this->intervals()->delete();
    }

    public function setDefault()
    {
        $myGroup = $this->myGroup();
        $calledClass = get_called_class();

        $currentDefaults = $calledClass::query()
            ->byGroup($myGroup)
            ->isDefault()
            ->get();

        $currentDefaults->each(function($plan) {
            $plan->is_default = false;
            $plan->save();
        });

        $this->is_default = true;
        $this->save();
    }

    public function myGroup(): ?GroupContract
    {
        return empty($this->group) ? null : new \Sagitarius29\LaravelSubscriptions\Entities\Group($this->group);
    }

    public function changeToGroup(GroupContract $group): void
    {
        $this->group = $group;

        if (! self::defaultExists($group)) {
            $this->is_default = true;
        }

        $this->save();
    }

    private static function defaultExists(GroupContract $group = null): bool
    {
        $calledClass = get_called_class();

        return $calledClass::query()
            ->byGroup($group)
            ->isDefault()
            ->exists();
    }
}
