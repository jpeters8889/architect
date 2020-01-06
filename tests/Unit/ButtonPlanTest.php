<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Button;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;

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
    public function it_updates_the_model()
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
    public function it_can_have_a_on_click_callback_set()
    {
        $this->plan->onClick(function () {
            return 'foo';
        });

        $this->assertEquals('foo', $this->plan->handleUpdate(new User()));
    }
}
