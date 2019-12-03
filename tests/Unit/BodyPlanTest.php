<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Body;
use JPeters\Architect\Tests\Abstracts\PlanTest;

class BodyPlanTest extends PlanTest
{
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
        $this->assertEquals(['rows' => 20], $this->plan->getMetas());
    }
}
