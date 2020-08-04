<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Facades\Hash;
use JPeters\Architect\Plans\Password;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

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
