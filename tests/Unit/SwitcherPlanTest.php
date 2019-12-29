<?php

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Support\Collection;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Plans\Switcher;
use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class SwitcherPlanTest extends PlanTestCase
{
    /** @var Switcher */
    protected $plan;

    public function getPlan()
    {
        return Switcher::class;
    }

    public function getColumnName()
    {
        return 'switcher';
    }

    /** @test */
    public function it_can_have_plans_set_for_options()
    {
        $this->plan->addPlansForOption(1, $plans = [
           new Textfield('foo'),
           new Textfield('bar'),
        ]);

        $metas = $this->plan->getMetas();

        $this->assertArrayHasKey('options', $metas);
        $this->assertArrayHasKey('switches', $metas);
        $this->assertIsArray($metas['switches']);

        $switch = $metas['switches'][1];

        foreach ($plans as $index => $plan) {
            /** @var Plan $plan */
            $this->assertEquals($plan->getColumn(), $switch[$index]['name']);
            $this->assertEquals($plan->getLabel(), $switch[$index]['label']);
        }
    }

    /** @test */
    public function it_updates_the_model()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function it_updates_a_plan_when_marked_as_in_a_relationship()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function it_can_be_marked_as_being_from_a_relationship()
    {
        $this->markTestSkipped();
    }
}
