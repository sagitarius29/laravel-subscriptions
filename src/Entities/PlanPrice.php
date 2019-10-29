<?php

namespace Sagitarius29\LaravelSubscriptions\Entities;

use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanPriceContract;
use Sagitarius29\LaravelSubscriptions\Exceptions\PriceErrorException;

class PlanPrice extends Model implements PlanPriceContract
{
    protected $table = 'plan_prices';

    protected $fillable = [
        'amount', 'interval', 'interval_unit',
    ];

    public static $DAY = 'day';
    public static $MONTH = 'month';
    public static $YEAR = 'year';

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public static function make($interval, int $intervalUnit, float $amount): PlanPriceContract
    {
        self::checkPriceInterval($interval);

        $attributes = [
            'amount'        => $amount,
            'interval'      => $interval,
            'interval_unit' => $intervalUnit,
        ];

        return new self($attributes);
    }

    public static function makeWithoutInterval(float $amount): PlanPriceContract
    {
        return new self([
            'amount'    => $amount,
        ]);
    }

    public function getInterval(): string
    {
        return $this->interval;
    }

    public function getIntervalUnit(): int
    {
        return $this->interval_unit;
    }

    protected static function checkPriceInterval(string $interval)
    {
        $intervals = [
            self::$DAY, self::$MONTH, self::$YEAR,
        ];
        if (! in_array($interval, $intervals)) {
            throw new PriceErrorException(
                'The interval \''.$interval.'\' is not correct. Available intervals are: '.implode(', ', $intervals)
            );
        }
    }
}
