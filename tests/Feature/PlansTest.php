<?php

namespace Orchestra\Testbench\Tests\Databases;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;
use Sagitarius29\LaravelSubscriptions\Entities\PlanInterval;
use Sagitarius29\LaravelSubscriptions\Entities\PlanFeature;
use Sagitarius29\LaravelSubscriptions\Traits\HasManyIntervals;
use Sagitarius29\LaravelSubscriptions\Traits\HasSingleInterval;
use Sagitarius29\LaravelSubscriptions\Tests\Entities\PlanManyIntervals;

class PlansTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_plan()
    {
        $attributes = [
            'name'          => 'Plan One',
            'description'   => $this->faker->sentence,
            'free_days'     => 0,
            'sort_order'    => 1,
        ];

        // create a plan
        $plan = Plan::create(
            $attributes['name'],
            $attributes['description'],
            $attributes['free_days'],
            $attributes['sort_order']
        );

        $this->assertDatabaseHas((new Plan)->getTable(), $attributes);

        $this->assertTrue($plan->features()->count() == 0);
        $this->assertTrue($plan->intervals()->count() == 0);
        $this->assertNotTrue($plan->hasManyIntervals());
        $this->assertTrue($plan->subscriptions()->count() == 0);

        // it's for default any plans are inactive
        $this->assertFalse($plan->isActive());

        // it's is free
        $this->assertTrue($plan->isFree());

        // it's default because it's only one
        $this->assertTrue($plan->isDefault());

        // it's group is null
        $this->assertNull($plan->myGroup());
    }

    /** @test */
    public function it_can_add_features_in_a_plan()
    {
        $plan = Plan::create(
            'name of plan',
            'this is a description',
            0,
            1
        );

        $features = [
            PlanFeature::make('listings', 50, 1, true),
            PlanFeature::make('pictures_per_listing', 10, 1, true),
            PlanFeature::make('listing_duration_days', 30, 1, true),
            PlanFeature::make('listing_title_bold', true, 1),
        ];

        // adding features to plan
        $plan->features()->saveMany($features);

        foreach ($features as $feature) {
            $this->assertDatabaseHas((new PlanFeature())->getTable(), $feature->toArray());
        }

        $this->assertTrue($plan->features()->count() == 4);
    }

    /** @test */
    public function it_can_set_and_change_interval_of_a_plan()
    {
        $plan = Plan::create(
            'name of plan',
            'this is a description',
            0,
            1
        );

        // it's object has single interval trait
        $this->assertTrue(in_array(HasSingleInterval::class, class_uses($plan)));

        // Interval is not free
        $firstInterval = PlanInterval::make(PlanInterval::$MONTH, 1, 10.50);

        $plan->setInterval($firstInterval);

        $this->assertInstanceOf(PlanInterval::class, $plan->getInterval());

        $this->assertDatabaseHas((new PlanInterval())->getTable(), $firstInterval->toArray());

        $this->assertTrue($plan->isNotFree());

        // it can change interval
        $otherInterval = PlanInterval::make(PlanInterval::$DAY, 15, 50.00);

        $plan->setInterval($otherInterval);

        $this->assertDatabaseMissing((new PlanInterval())->getTable(), $firstInterval->toArray());
        $this->assertDatabaseHas((new PlanInterval())->getTable(), $otherInterval->toArray());

        $this->assertEquals(PlanInterval::$DAY, $plan->getInterval()->getType());
        $this->assertEquals(15, $plan->getInterval()->getUnit());
        $this->assertEquals(50.00, $plan->getInterval()->getPrice());

        // it's changing to free
        $plan->setFree();

        $this->assertTrue($plan->isFree());

        $this->assertDatabaseMissing((new PlanInterval())->getTable(), $otherInterval->toArray());

        //the interval price is zero
        $intervalWithoutPrice = PlanInterval::make(PlanInterval::$DAY, 15, 0.00);

        $plan->setInterval($intervalWithoutPrice);

        $this->assertTrue($plan->isFree());
        $this->assertEquals(0.00, $plan->getInterval()->getPrice());
    }

    /** @test */
    public function a_plan_may_has_many_intervals()
    {
        $intervalsTable = (new PlanInterval())->getTable();

        // Need change config
        $this->app['config']->set('subscriptions.entities.plan', PlanManyIntervals::class);

        $plan = PlanManyIntervals::create(
            'name of plan',
            'this is a description',
            0,
            1
        );

        $this->assertTrue(Plan::query()->isDefault()->count() == 1);

        // this is object has trait of many intervals
        $this->assertTrue(in_array(HasManyIntervals::class, class_uses($plan)));

        $intervals = [
            PlanInterval::make(PlanInterval::$MONTH, 1, 4.90),
            PlanInterval::make(PlanInterval::$MONTH, 3, 11.90),
            PlanInterval::make(PlanInterval::$YEAR, 1, 49.90),
        ];

        $plan->setIntervals($intervals);

        foreach ($intervals as $interval) {
            $this->assertDatabaseHas($intervalsTable, $interval->toArray());
        }

        $this->assertTrue(count($plan->intervals) == 3);
        $this->assertTrue($plan->hasManyIntervals());
        $this->assertTrue($plan->isDefault());

        // second option: addInterval()
        $otherPlan = PlanManyIntervals::create(
            'other name of plan',
            'this is a description',
            0,
            1
        );

        $firstInterval = PlanInterval::make(PlanInterval::$MONTH, 1, 4.90);
        $otherPlan->addInterval($firstInterval);

        $secondInterval = PlanInterval::make(PlanInterval::$YEAR, 1, 49.90);
        $otherPlan->addInterval($secondInterval);

        $this->assertDatabaseHas($intervalsTable, $firstInterval->toArray());
        $this->assertDatabaseHas($intervalsTable, $secondInterval->toArray());

        $this->assertTrue($otherPlan->isNotFree());
        $this->assertTrue($otherPlan->hasManyIntervals());
        $this->assertNotTrue($otherPlan->isDefault());
    }
}
