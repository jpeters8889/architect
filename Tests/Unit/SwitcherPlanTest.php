<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Plans\Switcher;
use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

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
    public function itCanHavePlansSetForOptions()
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
            /* @var Plan $plan */
            $this->assertEquals($plan->getColumn(), $switch[$index]['name']);
            $this->assertEquals($plan->getLabel(), $switch[$index]['label']);
        }
    }

    /** @test */
    public function itUpdatesTheModel()
    {
        $this->plan->addPlansForOption(1, $plans = [
            new Textfield('foo'),
            new Textfield('bar'),
        ]);

        $user = new User();

        foreach ($plans as $index => $plan) {
            /* @var Plan $plan */
            $plan->handleUpdate($user, $plan->getColumn(), $plan->getColumn());
            $this->assertEquals($plan->getColumn(), $user->{$plan->getColumn()});
        }
    }
}
