<?php

namespace JPeters\Architect\Tests\Traits;

use JPeters\Architect\Tests\Laravel\Models\User;

trait LogsInUses
{
    protected function logIn(User $user = null)
    {
        if (!$user) {
            $user = factory(User::class)->create();
        }

        $this->actingAs($user, 'api');

        $this->withHeader('Authorization', 'Bearer: '.$user->api_token);
    }
}
