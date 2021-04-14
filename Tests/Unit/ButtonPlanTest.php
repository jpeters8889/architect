<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Button;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

class ButtonPlanTest extends PlanTestCase
{
    /** @var Button */
    protected $plan;

    public function getPlan()
    {
        return Button::class;
    }

    public function getColumnName()
    {
        return 'button';
    }

    /** @test */
    public function itUpdatesTheModel()
    {
        $user = new User();

        $this->plan->onClick(function (User $user) {
            $user->foo = 'bar';
        });

        $this->plan->handleUpdate($user);

        $this->assertNotNull($user->foo);
        $this->assertEquals('bar', $user->foo);
    }

    /** @test */
    public function itCanHaveAOnClickCallbackSet()
    {
        $this->plan->onClick(function () {
            return 'foo';
        });

        $this->assertEquals('foo', $this->plan->handleUpdate(new User()));
    }

    /** @test */
    public function itCanBeMarkedAsBeingAClickButton()
    {
        $this->assertArrayHasKey('click_method', $this->plan->getMetas());

        $this->assertEquals('ajax', $this->plan->getMetas()['click_method']);

        $this->plan->openAsLink();

        $this->assertEquals('link', $this->plan->getMetas()['click_method']);
    }
}
