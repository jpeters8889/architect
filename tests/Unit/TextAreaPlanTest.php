<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Textarea;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

class TextAreaPlanTest extends PlanTestCase
{
    use TestsPlanRelationships;

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
        $this->assertArrayHasKey('rows', $this->plan->getMetas());

        $this->assertEquals(3, $this->plan->getMetas()['rows']);

        $this->plan->rows($rows = 10);

        $this->assertEquals($rows, $this->plan->getMetas()['rows']);
    }
}
