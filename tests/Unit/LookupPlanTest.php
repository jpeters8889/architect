<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Lookup;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class LookupPlanTest extends PlanTestCase
{
    /** @var Lookup */
    protected $plan;

    public function getPlan()
    {
        return Lookup::class;
    }

    public function getColumnName()
    {
        return 'Lookup';
    }

    /** @test */
    public function it_can_have_an_action_set()
    {
        $this->plan->lookupAction(static function () {
            return 'foo';
        });

        $this->assertEquals('foo', $this->plan->performLookup(''));
    }

    /** @test */
    public function it_passes_a_value_to_the_action_callback()
    {
        $this->plan->lookupAction(static function ($value) {
            return $value;
        });

        $this->assertEquals('foo', $this->plan->performLookup('foo'));
    }
}
