<?php

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Support\Facades\Hash;
use JPeters\Architect\Plans\Password;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;
use JPeters\Architect\Tests\Traits\TestsPlanRelationships;

class PasswordPlanTest extends PlanTestCase
{
    public function getPlan()
    {
        return Password::class;
    }

    public function getColumnName()
    {
        return 'password';
    }

    public function it_updates_the_model()
    {
        /** @var User $user */
        $user = factory(User::class)->create();

        $class = $this->getPlan();

        /** @var Plan $plan */
        $plan = new $class('email');

        $plan->handleUpdate($user, 'password', 'foo');

        $this->assertNotEquals('foo', $user->password);
        $this->assertTrue(Hash::check('foo', $user->password));
    }
}
