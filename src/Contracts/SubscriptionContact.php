<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

interface SubscriptionContact
{
    public function scopeCurrent(Builder $q, Carbon $date);

    public function isPerpetual(): bool;

    public function getDaysLeft(): int;

    public function getElapsedDays(): int;

    public function getExpirationDate(): ?Carbon;

    public function getStartDate(): ?Carbon;

    public function subscriber();

    public static function make(PlanContract $plan, Carbon $start_at, Carbon $end_at = null): Model;
}
