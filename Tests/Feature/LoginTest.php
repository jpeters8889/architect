<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;

class LoginTest extends ArchitectTestCase
{
    /** @test */
    public function it_errors_when_trying_to_login_an_unknown_user()
    {
        $this->post('/architect/api/auth', [
            'email' => 'foo',
            'password' => 'bar',
        ])
            ->assertSessionHasErrors();
    }

    /** @test */
    public function it_logs_in_a_user()
    {
        factory(User::class)->create([
            'email' => 'jamie@jamie-peters.co.uk'
        ]);

        $this->withoutExceptionHandling()
            ->post('/architect/api/auth', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'secret'
        ])->assertStatus(200);

        $this->assertAuthenticated();
    }
}
