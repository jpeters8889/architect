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
    public function it_can_have_standard_options_set()
    {
        $this->plan->options(['One', 'Two', 'Three']);

        $this->assertArrayHasKey('options', $this->plan->getMetas());
    }

    /** @test */
    public function it_returns_the_set_standard_options()
    {
        $options = ['One', 'Two', 'Three'];

        $this->plan->options($options);
        $metas = $this->plan->getMetas();

        foreach ($options as $option) {
            $this->assertContains($option, $metas['options']);
        }
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

    /** @test */
    public function it_sets_has_lookup_as_true_when_one_is_set()
    {
        $this->assertArrayHasKey('hasLookup', $this->plan->getMetas());
        $this->assertFalse($this->plan->getMetas()['hasLookup']);

        $this->plan->lookupAction(static function () {
            return 'foo';
        });

        $this->assertTrue($this->plan->getMetas()['hasLookup']);
    }
}
