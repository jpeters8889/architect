<?php

namespace JPeters\Architect\Tests\Feature;

use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Models\User;

class LoginTest extends ArchitectTest
{
    /** @test */
    public function it_redirects_to_the_login_page_when_not_authenticated()
    {
        $this->get('/architect')
            ->assertRedirect('/architect/login');
    }

    /** @test */
    public function it_shows_the_login_page()
    {
        $this->get('/architect/login')
            ->assertStatus(200);
    }

    /** @test */
    public function it_errors_when_trying_to_login_an_unknown_user()
    {
        $this->post('/architect/login', [
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
            ->post('/architect/login', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'secret'
        ])->assertStatus(302);

        $this->assertAuthenticated();
    }
}
