<?php

namespace Sagitarius29\LaravelSubscriptions\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Entities\PlanFeature;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;

class PlanFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_feature()
    {
        $firstFeature = PlanFeature::make(
            'foo',
            true, // it's not consumable because it's boolean
            1
        );

        $secondFeature = PlanFeature::make(
            'foo',
            5, // is consumable
            2
        );

        $plan = factory(Plan::class)->create();
        $plan->addFeature($firstFeature);
        $plan->addFeature($secondFeature);

        $this->assertEquals($plan->id, $firstFeature->plan->id);
        $this->assertTrue($firstFeature->getValue());
        $this->assertNotTrue($firstFeature->isConsumable());

        $this->assertTrue($secondFeature->isConsumable());
    }
}
