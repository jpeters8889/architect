<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Lookup;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

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
    public function itCanHaveAnActionSet()
    {
        $this->plan->lookupAction(static function () {
            return 'foo';
        });

        $this->assertEquals('foo', $this->plan->performLookup(''));
    }

    /** @test */
    public function itPassesAValueToTheActionCallback()
    {
        $this->plan->lookupAction(static function ($value) {
            return $value;
        });

        $this->assertEquals('foo', $this->plan->performLookup('foo'));
    }
}
