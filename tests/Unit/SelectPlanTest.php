<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Select;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class SelectPlanTest extends PlanTestCase
{
    /** @var Select */
    protected $plan;

    public function getPlan()
    {
        return Select::class;
    }

    public function getColumnName()
    {
        return 'select';
    }

    /** @test */
    public function it_has_options_set()
    {
        $this->plan->options(['One', 'Two', 'Three']);

        $this->assertArrayHasKey('options', $this->plan->getMetas());
    }

    /** @test */
    public function it_returns_the_set_options()
    {
        $options = ['One', 'Two', 'Three'];

        $this->plan->options($options);
        $metas = $this->plan->getMetas();

        foreach ($options as $option) {
            $this->assertContains($option, $metas['options']);
        }
    }
}
