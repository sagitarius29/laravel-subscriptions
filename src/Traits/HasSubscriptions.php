<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Carbon\Carbon;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanIntervalContract;
use Sagitarius29\LaravelSubscriptions\Entities\PlanInterval;
use Sagitarius29\LaravelSubscriptions\Entities\Subscription;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscriptionContact;

trait HasSubscriptions
{
    public function subscriptions()
    {
        return $this->morphMany(config('subscriptions.entities.plan_subscription'), 'subscriber');
    }

    public function subscribeTo(PlanContract $plan)
    {
        $currentSubscription = $this->getCurrentSubscription();
        $start_at = null;
        $end_at = null;

        if ($currentSubscription == null) {
            $start_at = now();
        } else {
            $start_at = $currentSubscription->getExpirationDate();
        }

        if ($plan->isFree()) {
            $end_at = null;
        } else {
            if(!$plan->hasManyIntervals()) {
                $end_at = $this->calculateExpireDate($start_at, $plan->intervals()->first());
            }
        }

        $subscription = Subscription::make($plan, $start_at, $end_at);
        $subscription = $this->subscriptions()->save($subscription);

        return $subscription;
    }

    private function calculateExpireDate(Carbon $start_at, PlanIntervalContract $interval)
    {
        $end_at = Carbon::createFromTimestamp($start_at->timestamp);

        switch($interval->getType()) {
            case PlanInterval::$DAY:
                return $end_at->days($interval->getUnit());
                break;
            case PlanInterval::$MONTH:
                return $end_at->addMonths($interval->getUnit());
                break;
            case PlanInterval::$YEAR:
                return $end_at->addYears($interval->getUnit());
                break;
            default:
                //TODO error exception
                break;
        }
    }

    public function changePlanTo(PlanContract $plan)
    {
    }

    public function renewSubscription(): bool
    {
    }

    public function cancelSubscription(): bool
    {
    }

    public function getCurrentSubscription(): ?SubscriptionContact
    {
        return $this->subscriptions()
            ->current(now())
            ->first();
    }

    public function getConsumables()
    {
    }
}
