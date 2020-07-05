<?php

namespace JPeters\Architect\Tests\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use JPeters\Architect\Tests\Laravel\Models\User;

trait LogsInUsers
{
    protected function logIn(Authenticatable $user = null)
    {
        if (!$user) {
            $user = factory(User::class)->create();
        }

        $this->actingAs($user, 'api');

        $this->withHeader('Authorization', 'Bearer: '.$user->api_token);
    }
}
