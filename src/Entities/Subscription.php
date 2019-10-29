<?php


namespace Sagitarius29\LaravelSubscriptions\Entities;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscriptionContact;

class Subscription extends Model implements SubscriptionContact
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'plan_id', 'start_at', 'end_at'
    ];

    public function scopeCurrent(Builder $q, Carbon $date)
    {
        return $q->where('start_at', '<', $date)
            ->where('end_at', '>', $date);
    }

    public function isPerpetual(): bool
    {
        return $this->end_at == null;
    }

    public function getDaysLeft(): int
    {
        // TODO: Implement getDaysLeft() method.
    }

    public function getElapsedDays(): int
    {
        // TODO: Implement getElapsedDays() method.
    }

    public function getExpirationDate(): ?Carbon
    {
        // TODO: Implement getExpirationDate() method.
    }

    public function getStartDate(): ?Carbon
    {
        // TODO: Implement getStartDate() method.
    }

    public function subscriber()
    {
        // TODO: Implement subscriber() method.
    }

    public static function make(PlanContract $plan, Carbon $start_at, Carbon $end_at = null): Model
    {
        return new Subscription([
            'plan_id'   => $plan->id,
            'start_at'  => $start_at,
            'end_at'    => $end_at
        ]);
    }
}
