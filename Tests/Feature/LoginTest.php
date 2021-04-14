<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Models\User;

class LoginTest extends ArchitectTestCase
{
    /** @test */
    public function itErrorsWhenTryingToLoginAnUnknownUser()
    {
        $this->post('/architect/api/auth', [
            'email' => 'foo',
            'password' => 'bar',
        ])->assertSessionHasErrors();
    }

    /** @test */
    public function itLogsInAUser()
    {
        factory(User::class)->create([
            'email' => 'jamie@jamie-peters.co.uk',
        ]);

        $this->withoutExceptionHandling()
            ->post('/architect/api/auth', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'secret',
        ])->assertStatus(200);

        $this->assertAuthenticated();
    }
}
