<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Textarea;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;

class TextAreaPlanTest extends PlanTestCase
{
    /** @var Textarea */
    protected $plan;

    public function getPlan()
    {
        return Textarea::class;
    }

    public function getColumnName()
    {
        return 'textarea';
    }

    /** @test */
    public function it_can_set_the_number_of_rows()
    {
        $this->assertEquals(['rows' => 3], $this->plan->getMetas());

        $this->plan->rows($rows = 10);

        $this->assertEquals(['rows' => $rows], $this->plan->getMetas());
    }
}
