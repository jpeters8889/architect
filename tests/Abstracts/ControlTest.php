<?php

namespace JPeters\Architect\Tests\Abstracts;

use JPeters\Architect\Controls\Control;
use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Models\User;

abstract class ControlTest extends ArchitectTest
{
    /** @var Control */
    protected $control;

    protected function setUp(): void
    {
        parent::setUp();

        $class = $this->getControl();

        $this->control = new $class($this->getColumnName());
    }

    abstract public function getControl();

    abstract public function getColumnName();

    /** @test */
    public function it_can_be_hidden_from_the_index()
    {
        $this->control->hideOnIndex();

        $this->assertFalse($this->control->isAvailableOnIndex());
    }

    /** @test */
    public function it_can_be_hidden_from_forms()
    {
        $this->control->hideOnForms();

        $this->assertFalse($this->control->isAvailableOnForm());
    }

    /** @test */
    public function it_can_be_hidden_for_mobile_views()
    {
        $this->control->hideFromIndexOnMobile();

        $this->assertTrue($this->control->isHiddenOnMobile());
    }

    /** @test */
    public function it_sets_the_column_name()
    {
        $this->assertNotNull($this->control->getColumn());
        $this->assertEquals($this->getColumnName(), $this->control->getColumn());
    }

    /** @test */
    public function it_sets_the_label_name()
    {
        $class = $this->getControl();

        /** @var Control $control */
        $control = new $class('foo', 'Bar');

        $this->assertNotNull($control->getLabel());
        $this->assertEquals('Bar', $control->getLabel());
    }

    /** @test */
    public function it_sets_a_complex_label_name()
    {
        $class = $this->getControl();

        /** @var Control $control */
        $control = new $class('foo_bar');

        $this->assertEquals('Foo Bar', $control->getLabel());
    }

    /** @test */
    public function it_updates_the_model()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $class = $this->getControl();

        /** @var Control $control */
        $control = new $class('email');

        $control->handleUpdate($user, 'email', 'foo');

        $this->assertEquals('foo', $user->email);
    }
}
