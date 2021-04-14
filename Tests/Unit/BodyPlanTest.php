<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Body;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;
use JPeters\Architect\TestHelpers\Traits\TestsPlanRelationships;

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
    public function itReturnsTheNumberOfRowsInTheMetas()
    {
        $this->assertArrayHasKey('rows', $this->plan->getMetas());
        $this->assertEquals(20, $this->plan->getMetas()['rows']);
    }
}
