<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanIntervalContract;
use Sagitarius29\LaravelSubscriptions\Exceptions\IntervalErrorException;

class PlanInterval extends Model implements PlanIntervalContract
{
    protected $table = 'plan_intervals';
    protected $fillable = [
        'price', 'interval', 'interval_unit',
    ];

    public static function make($type, int $unit, float $price): PlanIntervalContract
    {
        self::checkIfIntervalExists($type);

        $attributes = [
            'price' => $price,
            'interval' => $type,
            'interval_unit' => $unit,
        ];

        return new self($attributes);
    }

    protected static function checkIfIntervalExists(string $interval)
    {
        $intervals = [
            self::DAY, self::MONTH, self::YEAR,
        ];
        if (! in_array($interval, $intervals)) {
            throw new IntervalErrorException(
                '\''.$interval.'\' is not correct. Available intervals are: '.implode(', ', $intervals)
            );
        }
    }

    public static function makeInfinite(float $price): PlanIntervalContract
    {
        return new self([
            'price' => $price,
        ]);
    }

    public function plan()
    {
        return $this->belongsTo(config('subscriptions.entities.plan'));
    }

    public function getType(): string
    {
        return $this->interval;
    }

    public function getUnit(): int
    {
        return $this->interval_unit;
    }

    public function isInfinite(): bool
    {
        return $this->interval == null;
    }

    public function isFree(): bool
    {
        return $this->getPrice() == 0;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function isNotFree(): bool
    {
        return $this->getPrice() != 0;
    }
}
