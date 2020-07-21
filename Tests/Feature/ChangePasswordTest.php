<?php

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Models\User;

class ChangePasswordTest extends ArchitectTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        factory(User::class)->create([
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs(User::query()->first());
    }

    /** @test */
    public function it_errors_when_trying_to_change_a_password_when_the_current_password_is_incorrect()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'foo',
            'new_password' => 'foobarbaz',
            'new_password_confirmation' => 'foobarbaz'
        ])->assertStatus(422);
    }

    /** @test */
    public function it_errors_when_the_new_passwords_dont_match()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'foobarbaz',
            'new_password_confirmation' => 'bazbarfoo'
        ])->assertStatus(422);
    }

    /** @test */
    public function it_errors_when_the_new_password_is_too_short()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'foo',
            'new_password_confirmation' => 'foo'
        ])->assertStatus(422);
    }

    /** @test */
    public function it_returns_success_when_valid_data_is_sent()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'foobarbaz',
            'new_password_confirmation' => 'foobarbaz'
        ])->assertStatus(200);
    }

    /** @test */
    public function it_successfully_changes_the_users_password()
    {
        /** @var User $user */
        $user = User::first();

        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'new-password',
            'new_password_confirmation' => 'new-password'
        ]);

        $this->assertNotEquals($user->password, $user->fresh()->password);

        $this->post('/architect/api/logout');

        $this->post('/architect/api/auth', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'old-password'
        ])->assertSessionHasErrors();

        $this->post('/architect/api/auth', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'new-password'
        ])->assertStatus(200);
    }
}
