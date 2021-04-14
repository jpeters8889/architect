<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Select;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

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
    public function itCanHaveStandardOptionsSet()
    {
        $this->plan->options(['One', 'Two', 'Three']);

        $this->assertArrayHasKey('options', $this->plan->getMetas());
    }

    /** @test */
    public function itReturnsTheSetStandardOptions()
    {
        $options = ['One', 'Two', 'Three'];

        $this->plan->options($options);
        $metas = $this->plan->getMetas();

        foreach ($options as $option) {
            $this->assertContains($option, $metas['options']);
        }
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

    /** @test */
    public function itSetsHasLookupAsTrueWhenOneIsSet()
    {
        $this->assertArrayHasKey('hasLookup', $this->plan->getMetas());
        $this->assertFalse($this->plan->getMetas()['hasLookup']);

        $this->plan->lookupAction(static function () {
            return 'foo';
        });

        $this->assertTrue($this->plan->getMetas()['hasLookup']);
    }
}
