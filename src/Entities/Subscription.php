<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscriptionContact;
use Sagitarius29\LaravelSubscriptions\Exceptions\SubscriptionErrorException;

class Subscription extends Model implements SubscriptionContact
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'plan_id', 'start_at', 'end_at',
    ];

    protected $dates = [
        'start_at', 'end_at',
    ];

    public static function make(PlanContract $plan, Carbon $start_at, Carbon $end_at = null): Model
    {
        if (! $plan instanceof Model) {
            throw new SubscriptionErrorException('$plan must be '.Model::class);
        }

        return new self([
            'plan_id' => $plan->id,
            'start_at' => $start_at,
            'end_at' => $end_at,
        ]);
    }

    public function scopeCurrent(Builder $q)
    {
        $date = now();

        return $q->where('start_at', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->where('end_at', '>=', $date)->orWhereNull('end_at');
            });
    }

    public function getDaysLeft(): ?int
    {
        if ($this->isPerpetual()) {
            return null;
        }

        return now()->diffInDays($this->end_at);
    }

    public function isPerpetual(): bool
    {
        return $this->end_at == null;
    }

    public function getElapsedDays(): int
    {
        return now()->diffInDays($this->start_at);
    }

    public function getExpirationDate(): ?Carbon
    {
        return $this->end_at;
    }

    public function getStartDate(): Carbon
    {
        return $this->start_at;
    }

    public function subscriber()
    {
        return $this->morphTo();
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(config('subscriptions.entities.plan'));
    }
}
