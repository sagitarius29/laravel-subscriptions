<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Carbon\Carbon;
use Sagitarius29\LaravelSubscriptions\Entities\PlanInterval;
use Sagitarius29\LaravelSubscriptions\Entities\Subscription;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscriptionContact;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanIntervalContract;
use Sagitarius29\LaravelSubscriptions\Exceptions\SubscriptionErrorException;

trait HasSubscriptions
{
    /**
     * @param  PlanContract|PlanIntervalContract  $planOrInterval
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function subscribeTo($planOrInterval)
    {
        if ($planOrInterval instanceof PlanContract) {
            return $this->subscribeToPlan($planOrInterval);
        }

        return $this->subscribeToInterval($planOrInterval);
    }

    public function subscribeToPlan(PlanContract $plan)
    {
        if ($plan->hasManyIntervals()) {
            throw new SubscriptionErrorException(
                'This plan has many intervals, please use subscribeToInterval() function'
            );
        }

        $currentSubscription = $this->getActiveSubscription();
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
            $end_at = $this->calculateExpireDate($start_at, $plan->intervals()->first());
        }

        $subscription = Subscription::make($plan, $start_at, $end_at);
        $subscription = $this->subscriptions()->save($subscription);

        return $subscription;
    }

    public function getActiveSubscription(): ?SubscriptionContact
    {
        return $this->subscriptions()
            ->current()
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->current()
            ->exists();
    }

    public function subscriptions()
    {
        return $this->morphMany(config('subscriptions.entities.plan_subscription'), 'subscriber');
    }

    private function calculateExpireDate(Carbon $start_at, PlanIntervalContract $interval)
    {
        $end_at = Carbon::createFromTimestamp($start_at->timestamp);

        switch ($interval->getType()) {
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

    public function subscribeToInterval(PlanIntervalContract $interval): SubscriptionContact
    {
        $currentSubscription = $this->getActiveSubscription();
        $start_at = null;
        $end_at = null;

        if ($currentSubscription == null) {
            $start_at = now();
        } else {
            $start_at = $currentSubscription->getExpirationDate();
        }

        $end_at = $this->calculateExpireDate($start_at, $interval);

        $subscription = Subscription::make($interval->plan, $start_at, $end_at);
        $subscription = $this->subscriptions()->save($subscription);

        return $subscription;
    }

    public function changePlanTo(PlanContract $plan, PlanIntervalContract $interval = null)
    {
        if (! $this->hasActiveSubscription()) {
            throw new SubscriptionErrorException('You need a subscription for upgrade to other.');
        }

        if ($plan->hasManyIntervals() && $interval == null) {
            throw new SubscriptionErrorException('The plan has many intervals, please indicate a interval.');
        }

        $currentSubscription = $this->getActiveSubscription();
        $currentPlan = $currentSubscription->plan;
        $currentIntervalPrice = $currentPlan->isFree() ? 0.00 : $currentPlan->getInterval()->getPrice();

        $toInterval = $plan->getInterval();

        if ($currentPlan->id == $plan->id) {
            throw new SubscriptionErrorException('You can\'t change to same plan. You need change to other plan.');
        }

        if ($interval !== null) {
            $toInterval = $interval;
        }

        if ($currentIntervalPrice < $toInterval->getPrice()) {
            return $this->upgradeTo($toInterval);
        }

        return $this->downgradeTo($toInterval);
    }

    protected function upgradeTo(PlanIntervalContract $interval): SubscriptionContact
    {
        if(! $this->hasActiveSubscription()) {
            throw new SubscriptionErrorException('You need a subscription for upgrade to other.');
        }

        $this->forceCancelSubscription();

        return $this->subscribeToInterval($interval);
    }

    protected function downgradeTo(PlanIntervalContract $interval): SubscriptionContact
    {
        if(! $this->hasActiveSubscription()) {
            throw new SubscriptionErrorException('You need a subscription for upgrade to other.');
        }

        return $this->subscribeToInterval($interval);
    }

    public function renewSubscription(PlanIntervalContract $interval = null)
    {
        $currentSubscription = $this->getActiveSubscription();

        if ($interval === null) {
            $plan = $currentSubscription->plan;

            if ($plan->hasManyIntervals()) {
                throw new SubscriptionErrorException(
                    'The plan you want will subscribe has many intervals, please consider renew to a interval of plan'
                );
            }

            $interval = $plan->intervals()->first();
        }

        $newExpireDate = $this->calculateExpireDate($currentSubscription->end_at, $interval);

        $currentSubscription->end_at = $newExpireDate;
        $currentSubscription->save();

        return $currentSubscription;
    }

    public function cancelSubscription()
    {
        $currentSubscription = $this->getActiveSubscription();
        $currentSubscription->cancelled_at = now();
        $currentSubscription->save();
    }

    public function forceCancelSubscription()
    {
        $currentSubscription = $this->getActiveSubscription();
        $currentSubscription->end_at = now()->subSecond();
        $currentSubscription->cancelled_at = now();
        $currentSubscription->save();
    }

    public function getConsumables()
    {
    }
}
