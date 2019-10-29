<?php


namespace Sagitarius29\LaravelSubscriptions\Traits;


use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscribableContract;
use Sagitarius29\LaravelSubscriptions\Contracts\SubscriptionContact;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Entities\Subscription;

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

        if($currentSubscription == null) {
            $start_at = now();
        }

        if($plan->isFree()) {
            $end_at = null;
        }

        $subscription = Subscription::make($plan, $start_at, $end_at);
        $subscription = $this->subscriptions()->save($subscription);

        return $subscription;
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
