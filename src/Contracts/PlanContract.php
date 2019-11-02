<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PlanContract
{
    /**
     * @param  string  $name
     * @param  string  $description
     * @param  int  $free_days
     * @param  int  $sort_order
     * @param  bool  $is_active
     * @param  bool  $is_default
     * @param  GroupContract|null  $group
     * @return Model|PlanContract
     */
    public static function create(
        string $name,
        string $description,
        int $free_days,
        int $sort_order,
        bool $is_active = false,
        bool $is_default = false,
        GroupContract $group = null
    ): PlanContract;

    public function features();

    public function addFeature(PlanFeatureContract $feature);

    public function intervals();

    public function isDefault(): bool;

    public function isActive(): bool;

    public function isFree(): bool;

    public function isNotFree(): bool;

    public function hasManyIntervals(): bool;

    public function subscriptions();

    public function setDefault();

    public function myGroup(): ?GroupContract;

    public function changeToGroup(GroupContract $group): void;
}
