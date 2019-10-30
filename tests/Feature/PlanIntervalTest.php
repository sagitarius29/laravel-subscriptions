<?php

namespace Sagitarius29\LaravelSubscriptions\Tests\Feature;

use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;
use Sagitarius29\LaravelSubscriptions\Entities\PlanInterval;
use Sagitarius29\LaravelSubscriptions\Exceptions\IntervalErrorException;

class PlanIntervalTest extends TestCase
{
    /** @test */
    public function it_can_create_interval_for_plans()
    {
        //Error Interval

        $this->expectException(IntervalErrorException::class);

        PlanInterval::make(
            'faa',
            30,
            4.99
        );


        // Make Interval
        $interval = PlanInterval::make(
            PlanInterval::$DAY,
            30,
            4.99
        );
        $plan = factory(Plan::class)->create();
        $plan->setInterval($interval);

        $this->assertEquals($plan->id, $interval->plan->id);
        $this->assertEquals(PlanInterval::$DAY, $interval->getType());
        $this->assertEquals(30, $interval->getUnit());
        $this->assertEquals(4.99, $interval->getPrice());
        $this->assertNotTrue($interval->isInfinite());
        $this->assertTrue($interval->isNotFree());

        // Interval Free
        $interval = PlanInterval::make(
            PlanInterval::$DAY,
            30,
            0
        );

        $this->assertTrue($interval->isFree());

        // Infinity Interval Free
        $interval = PlanInterval::makeInfinite(
            0
        );
        $this->assertTrue($interval->isInfinite());
        $this->assertTrue($interval->isFree());

        // Infinity Interval Not Free
        $interval = PlanInterval::makeInfinite(
            50.00
        );
        $this->assertTrue($interval->isInfinite());
        $this->assertTrue($interval->isNotFree());
    }
}
