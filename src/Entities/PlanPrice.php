<?php


namespace Sagitarius29\LaravelSubscriptions\Entities;


use Illuminate\Database\Eloquent\Model;

class PlanPeriod extends Model
{
    protected $table = 'plan_periods';

    protected $fillable = [
        'price', 'period', 'interval'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
