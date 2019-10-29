<?php


namespace Sagitarius29\LaravelSubscriptions\Tests\Feature;


use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;
use Sagitarius29\LaravelSubscriptions\Tests\Entities\User;
use Sagitarius29\LaravelSubscriptions\Entities\Subscription;

class SubscriptionsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $now = '2019-10-20 00:00:00';

    /** @test */
    public function a_user_can_subscribe_to_a_plan_perpetual()
    {
        Carbon::setTestNow(Carbon::createFromFormat('Y-m-d H:i:s', $this->now));

        $user = factory(User::class)->create();
        $plan = factory(Plan::class)->create();

        $subscription = $user->subscribeTo($plan);

        // when plan is free and the plan has't prices
        $this->assertTrue($plan->isFree());
        $this->assertTrue($plan->prices()->count() == 0);

        $dataSubscription = [
            'plan_id'           => $plan->id,
            'subscriber_type'   => User::class,
            'subscriber_id'     => $user->id,
            'start_at'          => $this->now,
            'end_at'            => null,
        ];

        $this->assertDatabaseHas((new Subscription())->getTable(), $dataSubscription);

        // the subscription is perpetual
        $this->assertTrue($subscription->isPerpetual());

    }
}
