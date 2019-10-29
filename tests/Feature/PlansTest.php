<?php
namespace Orchestra\Testbench\Tests\Databases;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Sagitarius29\LaravelSubscriptions\{Entities\Plan,
    Entities\PlanFeature,
    Entities\PlanPrice,
    Tests\Entities\PlanManyPrices,
    Tests\Entities\User,
    Tests\TestCase,
    Traits\HasManyPrices,
    Traits\HasSinglePrice};

class PlansTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_plan()
    {
        $attributes = [
            'name'      => 'Plan One',
            'description'   => $this->faker->sentence,
            'free_days' => 0,
            'sort_order' => 1
        ];

        // create a plan
        $plan = Plan::create(
            $attributes['name'],
            $attributes['description'],
            $attributes['free_days'],
            $attributes['sort_order']
        );

        $this->assertDatabaseHas((new Plan)->getTable(), $attributes);

        // it's for default any plans are inactive
        $this->assertFalse($plan->isActive());

        // it's is free
        $this->assertTrue($plan->isFree());

        // it's default because it's only one
        $this->assertFalse($plan->isDefault());

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
    }

    /** @test */
    public function it_can_set_and_change_single_price_of_its_plan()
    {
        $plan = Plan::create(
            'name of plan',
            'this is a description',
            0,
            1
        );

        // it's object has single price trait
        $this->assertTrue(in_array(HasSinglePrice::class, class_uses($plan)));

        $firstPrice = PlanPrice::make(PlanPrice::$MONTH, 1, 10.50);

        $plan->setPrice($firstPrice);

        $this->assertInstanceOf(PlanPrice::class, $plan->getPrice());

        $this->assertDatabaseHas((new PlanPrice())->getTable(), $firstPrice->toArray());

        $this->assertTrue(!$plan->isFree());

        // it's changing price
        $otherPrice = PlanPrice::make(PlanPrice::$DAY, 15, 50.00);

        $plan->setPrice($otherPrice);

        $this->assertDatabaseMissing((new PlanPrice())->getTable(), $firstPrice->toArray());

        $this->assertDatabaseHas((new PlanPrice())->getTable(), $otherPrice->toArray());

        $this->assertEquals(50.00, $plan->getPrice()->getAmount());

        $this->assertEquals(PlanPrice::$DAY, $plan->getPrice()->getInterval());

        $this->assertEquals(15, $plan->getPrice()->getIntervalUnit());

        // it's changing to free
        $plan->setFree();

        $this->assertTrue($plan->isFree());

        $this->assertDatabaseMissing((new PlanPrice())->getTable(), $otherPrice->toArray());

        //the price is zero
        $zeroPrice = PlanPrice::make(PlanPrice::$DAY, 15, 0.00);

        $plan->setPrice($zeroPrice);

        $this->assertTrue($plan->isFree());
    }

    /** @test */
    public function a_plan_may_have_many_prices()
    {
        // Need change config
        $this->app['config']->set('subscriptions.entities.plan', PlanManyPrices::class);

        $plan = PlanManyPrices::create(
            'name of plan',
            'this is a description',
            0,
            1
        );

        // it's object has trait of many prices
        $this->assertTrue(in_array(HasManyPrices::class, class_uses($plan)));

        $prices = [
            PlanPrice::make(PlanPrice::$MONTH, 1, 4.90),
            PlanPrice::make(PlanPrice::$MONTH, 3, 11.90),
            PlanPrice::make(PlanPrice::$YEAR, 1, 49.90),
        ];

        $plan->setPrices($prices);

        foreach ($prices as $price) {
            $this->assertDatabaseHas((new PlanPrice())->getTable(), $price->toArray());
        }

        $this->assertTrue(count($plan->prices) == 3);

        // second option: add prices
        $otherPlan = PlanManyPrices::create(
            'other name of plan',
            'this is a description',
            0,
            1
        );

        $firstPrice = PlanPrice::make(PlanPrice::$MONTH, 1, 4.90);
        $otherPlan->addPrice($firstPrice);

        $secondPrice = PlanPrice::make(PlanPrice::$YEAR, 1, 49.90);
        $otherPlan->addPrice($secondPrice);

        $this->assertDatabaseHas((new PlanPrice())->getTable(), $firstPrice->toArray());
        $this->assertDatabaseHas((new PlanPrice())->getTable(), $secondPrice->toArray());
    }
}
