<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PlanContract
{
    public function features();

    public function intervals();

    public function isDefault(): bool;

    public function isActive(): bool;

    public function isFree(): bool;

    public function isNotFree(): bool;

    public function hasManyIntervals(): bool;

    public function subscriptions();

    public function myGroup(): ?GroupContract;

    public function toGroup(GroupContract $group): void;

    public static function create(
        string $name,
        string $description,
        int $free_days,
        int $sort_order,
        bool $is_active = false,
        bool $is_default = false
    ): Model;
}
