<?php

namespace Sagitarius29\LaravelSubscriptions\Tests\Feature;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Sagitarius29\LaravelSubscriptions\Entities\Group;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;

class GroupPlanTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_group_can_has_many_plans()
    {
        $firstGroup = new Group('first_group');
        $secondGroup = new Group('second_group');
        $thirdGroup = new Group('third_group');

        // option one
        $plans = [
            factory(Plan::class)->create(),
            factory(Plan::class)->create(),
            factory(Plan::class)->create(),
        ];

        $firstGroup->addPlans($plans);

        // option two
        $secondGroup->addPlan(factory(Plan::class)->create());
        $secondGroup->addPlan(factory(Plan::class)->create());

        // option three
        Plan::create(
            'Other Plan',
            'This is a description',
            15,
            1,
            true,
            true,
            $thirdGroup
        );

        $this->assertEquals(3, $firstGroup->plans()->count());
        $this->assertEquals(2, $secondGroup->plans()->count());
        $this->assertEquals(1, $thirdGroup->plans()->count());

        $this->assertEquals('first_group', $firstGroup);
    }

    /** @test */
    public function a_plan_can_change_group()
    {
        $firstGroup = new Group('first_group');
        $secondGroup = new Group('second_group');

        $planA = factory(Plan::class)->create();

        $this->assertNotTrue($firstGroup->hasPlans());
        $this->assertNotTrue($secondGroup->hasPlans());

        // option one
        $planA->changeToGroup($firstGroup);
        $this->assertEquals(1, $firstGroup->plans()->count());

        // option two
        $secondGroup->addPlan($planA);

        $this->assertEquals(0, $firstGroup->plans()->count());
        $this->assertEquals(1, $secondGroup->plans()->count());
    }

    /** @test */
    public function it_can_get_all_plans_of_one_group()
    {
        $firstGroup = new Group('first_group');

        $plans = [
            factory(Plan::class)->create(),
            factory(Plan::class)->create(),
            factory(Plan::class)->create(),
        ];

        $firstGroup->addPlans($plans);

        $allPlans = $firstGroup->plans()->get();

        $this->assertCount(3, $allPlans);
    }

    /** @test */
    public function it_can_get_default_plan_of_one_group()
    {
        $firstGroup = new Group('first_group');
        $secondGroup = new Group('second_group');

        $plans = [
            factory(Plan::class)->create(),
            factory(Plan::class)->create(),
            factory(Plan::class)->create(),
        ];

        $firstGroup->addPlans($plans);
        $secondGroup->addPlan(factory(Plan::class)->create());

        $this->assertEquals($plans[0]->id, $firstGroup->getDefaultPlan()->id);

        // change default plan
        $plans[2]->setDefault();

        $this->assertEquals($plans[2]->id, $firstGroup->getDefaultPlan()->id);
    }
}
