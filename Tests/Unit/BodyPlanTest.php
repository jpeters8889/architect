<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Body;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

class BodyPlanTest extends PlanTestCase
{
    use TestsPlanRelationships;

    public function getPlan()
    {
        return Body::class;
    }

    public function getColumnName()
    {
        return 'body';
    }

    /** @test */
    public function it_returns_the_number_of_rows_in_the_metas()
    {
        $this->assertArrayHasKey('rows', $this->plan->getMetas());
        $this->assertEquals(20, $this->plan->getMetas()['rows']);
    }
}