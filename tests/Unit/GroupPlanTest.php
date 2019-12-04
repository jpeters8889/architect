<?php

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Support\Collection;
use JPeters\Architect\Plans\Group;
use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTest;
use JPeters\Architect\Tests\Laravel\Models\User;

class GroupPlanTest extends PlanTest
{
    /** @var Group */
    protected $plan;

    /**
     * @var array
     */
    private $setPlans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plan->plans($this->setPlans = [
            new Textfield('name'),
            new Textfield('password'),
        ]);
    }

    public function getPlan()
    {
        return Group::class;
    }

    public function getColumnName()
    {
        return 'Group';
    }

    /** @test */
    public function it_can_have_plans_set()
    {
        $this->assertInstanceOf(Collection::class, $this->plan->getPlans());

        $this->plan->getPlans()->each(function ($plan, $index) {
            $this->assertEquals($this->setPlans[$index]->getLabel(), $plan['label']);
            $this->assertEquals($this->setPlans[$index]->getColumn(), $plan['column']);
            $this->assertEquals($this->setPlans[$index]->vuePrefix() . '-form', $plan['component']);
            $this->assertEquals($this->setPlans[$index]->getMetas(), $plan['metas']);
            $this->assertNull($plan['value']);
        });
    }

    /** @test */
    public function it_returns_plans_in_the_metas()
    {
        $this->assertArrayHasKey('plans', $this->plan->getMetas());
    }

    /** @test */
    public function it_returns_each_plans_value_when_the_current_model_is_set()
    {
        $user = factory(User::class)->create();

        $this->plan->getCurrentValue($user);

        $this->plan->getPlans()->each(function ($plan, $index) use ($user) {
            $this->assertEquals($user->{$this->setPlans[$index]->getColumn()}, $plan['value']);
        });
    }
}
