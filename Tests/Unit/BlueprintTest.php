<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\Blog;
use JPeters\Architect\Tests\Laravel\Blueprints\User;
use JPeters\Architect\Tests\Traits\LogsInUsers;

class BlueprintTest extends ArchitectTestCase
{
    use LogsInUsers;

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
        $this->logIn();

        $navigation = $this->architect->blueprintManager->renderForNavigation();

        $this->assertArrayHasKey('buildings', $navigation);
        $this->assertArrayHasKey('blueprints', $navigation);

        $building = $navigation['blueprints']['Main'];

        foreach ($this->blueprints as $index => $blueprint) {
            /** @var Blueprint $concreteBlueprint */
            $concreteBlueprint = new $blueprint();

            $this->assertArrayHasKey($concreteBlueprint->blueprintSite(), $navigation['blueprints']);

            $this->assertArrayHasKey('label', $building[$index]);
            $this->assertArrayHasKey('route', $building[$index]);
            $this->assertArrayHasKey('count', $building[$index]);

            $this->assertEquals($concreteBlueprint->blueprintName(), $building[$index]['label']);
            $this->assertEquals($concreteBlueprint->blueprintRoute(), $building[$index]['route']);
            $this->assertEquals($concreteBlueprint->displayCount(), $building[$index]['count']);
        }
    }
}
