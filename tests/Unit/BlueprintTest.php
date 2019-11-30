<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Blueprints\Blog;
use JPeters\Architect\Tests\Blueprints\User;

class BlueprintTest extends ArchitectTest
{
    /**
     * @var array
     */
    private $blueprints;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blueprints = [
            User::class,
            Blog::class,
        ];

        foreach ($this->blueprints as $blueprint) {
            $this->architect->registerBlueprint($blueprint);
        }
    }

    /** @test */
    public function it_registers_blueprints()
    {
        $this->assertCount(2, $this->architect->blueprintManager->blueprintList());
        $this->assertEquals(User::class, $this->architect->blueprintManager->blueprintList()[0]);
        $this->assertEquals(Blog::class, $this->architect->blueprintManager->blueprintList()[1]);
    }

    /** @test */
    public function it_resolves_a_blueprint()
    {
        $this->assertInstanceOf(User::class, $this->architect->blueprintManager->resolve('user'));
        $this->assertInstanceOf(Blog::class, $this->architect->blueprintManager->resolve('blog'));
    }

    /** @test */
    public function it_renders_the_blueprint_navigation()
    {
        $navigation = $this->architect->blueprintManager->renderForNavigation();

        foreach ($this->blueprints as $blueprint) {
            /** @var Blueprint $concreteBlueprint */
            $concreteBlueprint = new $blueprint();

            $this->assertStringContainsString($concreteBlueprint->url(), $navigation);
            $this->assertStringContainsString($concreteBlueprint->blueprintName(), $navigation);
        }
    }
}
