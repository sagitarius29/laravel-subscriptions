<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface SubscriptionContact
{
    public static function make(PlanContract $plan, Carbon $start_at, Carbon $end_at = null): Model;

    public function scopeCurrent(Builder $q);

    public function isPerpetual(): bool;

    public function getDaysLeft(): ?int;

    public function getElapsedDays(): int;

    public function getExpirationDate(): ?Carbon;

    public function getStartDate(): Carbon;

    public function plan();

    public function subscriber();
}
