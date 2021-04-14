<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Models\User;

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
    public function itErrorsWhenTryingToChangeAPasswordWhenTheCurrentPasswordIsIncorrect()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'foo',
            'new_password' => 'foobarbaz',
            'new_password_confirmation' => 'foobarbaz',
        ])->assertStatus(422);
    }

    /** @test */
    public function itErrorsWhenTheNewPasswordsDontMatch()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'foobarbaz',
            'new_password_confirmation' => 'bazbarfoo',
        ])->assertStatus(422);
    }

    /** @test */
    public function itErrorsWhenTheNewPasswordIsTooShort()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'foo',
            'new_password_confirmation' => 'foo',
        ])->assertStatus(422);
    }

    /** @test */
    public function itReturnsSuccessWhenValidDataIsSent()
    {
        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'foobarbaz',
            'new_password_confirmation' => 'foobarbaz',
        ])->assertStatus(200);
    }

    /** @test */
    public function itSuccessfullyChangesTheUsersPassword()
    {
        /** @var User $user */
        $user = User::first();

        $this->post('/architect/api/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'new-password',
            'new_password_confirmation' => 'new-password',
        ]);

        $this->assertNotEquals($user->password, $user->fresh()->password);

        $this->post('/architect/api/logout');

        $this->post('/architect/api/auth', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'old-password',
        ])->assertSessionHasErrors();

        $this->post('/architect/api/auth', [
            'email' => 'jamie@jamie-peters.co.uk',
            'password' => 'new-password',
        ])->assertStatus(200);
    }
}
