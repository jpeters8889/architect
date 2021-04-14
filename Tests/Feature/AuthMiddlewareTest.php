<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Support\Facades\Config;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User;
use JPeters\Architect\TestHelpers\Laravel\ArchitectGateway;
use JPeters\Architect\TestHelpers\Laravel\Models\User as UserModel;

class AuthMiddlewareTest extends ArchitectTestCase
{
    use LogsInUsers;

    /** @test */
    public function itErrorsWhenTryingToLoadApiAuthRoutesWhenNotLoggedIn()
    {
        $this->architect->registerBlueprint(User::class);

        $this->get('/architect/api/blueprints/user/list')->assertRedirect('/architect/login');

        $this->logIn();

        $this->get('/architect/api/blueprints/user/list')->assertStatus(200);
    }

    /** @test */
    public function itRejectsUsersWhenTheGatewayIsActive()
    {
        Config::set('architect.gateway', ArchitectGateway::class);

        $this->architect->registerBlueprint(User::class);

        $this->logIn(factory(UserModel::class)->create(['email' => 'not-valid@email.com']));
        $this->get('/architect/api/blueprints/user/list')->assertStatus(403);

        $this->logIn(factory(UserModel::class)->create(['email' => 'jamie@jamie-peters.co.uk']));
        $this->get('/architect/api/blueprints/user/list')->assertStatus(200);
    }
}
