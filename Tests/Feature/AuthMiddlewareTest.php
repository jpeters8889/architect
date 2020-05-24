<?php

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\ArchitectGateway;
use JPeters\Architect\Tests\Laravel\Blueprints\User;
use JPeters\Architect\Tests\Laravel\Models\User as UserModel;
use JPeters\Architect\Tests\Traits\LogsInUsers;

class AuthMiddlewareTest extends ArchitectTestCase
{
    use LogsInUsers;

    /** @test */
    public function it_errors_when_trying_to_load_api_auth_routes_when_not_logged_in()
    {
        $this->architect->registerBlueprint(User::class);

        $this->get('/architect/api/blueprints/user/list')->assertStatus(401);

        $this->logIn();

        $this->get('/architect/api/blueprints/user/list')->assertStatus(200);
    }

    /** @test */
    public function it_rejects_users_when_the_gateway_is_active()
    {
        Config::set('architect.gateway', ArchitectGateway::class);

        $this->architect->registerBlueprint(User::class);

        $this->logIn(factory(UserModel::class)->create(['email' => 'not-valid@email.com']));
        $this->get('/architect/api/blueprints/user/list')->assertStatus(403);

        $this->logIn(factory(UserModel::class)->create(['email' => 'jamie@jamie-peters.co.uk']));
        $this->get('/architect/api/blueprints/user/list')->assertStatus(200);
    }
}
