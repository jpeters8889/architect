<?php

namespace JPeters\Architect\Tests\Abstracts;

use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Models\User;

abstract class PlanTest extends ArchitectTest
{
    /** @var Plan */
    protected $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $class = $this->getPlan();

        $this->plan = new $class($this->getColumnName());
    }

    abstract public function getPlan();

    abstract public function getColumnName();

    /** @test */
    public function it_can_be_hidden_from_the_index()
    {
        $this->plan->hideOnIndex();

        $this->assertFalse($this->plan->isAvailableOnIndex());
    }

    /** @test */
    public function it_can_be_hidden_from_forms()
    {
        $this->plan->hideOnForms();

        $this->assertFalse($this->plan->isAvailableOnForm());
    }

    /** @test */
    public function it_can_be_hidden_for_mobile_views()
    {
        $this->plan->hideFromIndexOnMobile();

        $this->assertTrue($this->plan->isHiddenOnMobile());
    }

    /** @test */
    public function it_sets_the_column_name()
    {
        $this->assertNotNull($this->plan->getColumn());
        $this->assertEquals($this->getColumnName(), $this->plan->getColumn());
    }

    /** @test */
    public function it_sets_the_label_name()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('foo', 'Bar');

        $this->assertNotNull($plan->getLabel());
        $this->assertEquals('Bar', $plan->getLabel());
    }

    /** @test */
    public function it_sets_a_complex_label_name()
    {
        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('foo_bar');

        $this->assertEquals('Foo Bar', $plan->getLabel());
    }

    /** @test */
    public function it_updates_the_model()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('email');

        $plan->handleUpdate($user, 'email', 'foo');

        $this->assertEquals('foo', $user->email);
    }
}
