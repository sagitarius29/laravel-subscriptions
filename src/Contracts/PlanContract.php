<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PlanContract
{
    public static function create(
        string $name,
        string $description,
        int $free_days,
        int $sort_order,
        bool $is_active = false,
        bool $is_default = false,
        GroupContract $group = null
    ): Model;

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
