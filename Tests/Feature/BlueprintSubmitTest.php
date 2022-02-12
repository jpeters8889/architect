<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User as UserBlueprint;

class BlueprintSubmitTest extends ArchitectTestCase
{
    use LogsInUsers;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->architect->registerBlueprint(UserBlueprint::class);

        $this->logIn();
        $this->makeFaker('en_GB');
    }

    /** @test */
    public function itRejectsRequestsWithoutABlueprint()
    {
        $this->makeRequest(['_blueprint' => null])->assertSessionHasErrors(['_blueprint']);
    }

    /** @test */
    public function itRejectsRequestsWithAnUnknownBlueprint()
    {
        $this->makeRequest(['_blueprint' => 'foo'])
            ->assertStatus(404);
    }

    /** @test */
    public function itRejectsRequestsWithoutAState()
    {
        $this->makeRequest(['_state' => null])->assertSessionHasErrors(['_state']);
    }

    /** @test */
    public function itRejectsRequestsWithoutAnInvalidState()
    {
        $this->makeRequest(['_state' => 'foo'])->assertSessionHasErrors(['_state']);
    }

    /** @test */
    public function itRejectsRequestsWithoutAnIdWhenTheStateIsUpdate()
    {
        $this->makeRequest(['_state' => 'update'])->assertSessionHasErrors(['_id']);
    }

    /** @test */
    public function itCreatesARecordWhenSubmittingAValidAddRequest()
    {
        $this->makeRequest([
            'name' => $name = $this->faker->name,
            'email' => $email = $this->faker->safeEmail,
            'password' => $password = Str::random(8),
            'api_token' => $apiToken = Str::random(80),
        ])
            ->assertStatus(201)
            ->assertJson([
                'id' => 2,
                'blueprint' => 'Users',
                'url' => null,
            ]);

        $user = User::query()->find(2);

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($apiToken, $user->api_token);
    }

    /** @test */
    public function itUpdatesARecordWhenSubmittingAValidUpdateRequest()
    {
        $this->makeRequest([
            '_state' => 'update',
            '_id' => 1,
            'name' => $name = $this->faker->name,
            'email' => $email = $this->faker->safeEmail,
            'password' => $password = Str::random(8),
            'api_token' => $apiToken = Str::random(80),
        ])
            ->assertStatus(201)
            ->assertJson([
                'id' => 1,
                'blueprint' => 'Users',
                'url' => null,
            ]);

        $this->assertCount(1, User::query()->get());

        $user = User::query()->first();

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($password, $user->password);
        $this->assertEquals($apiToken, $user->api_token);
    }

    private function makeRequest($params = [])
    {
        return $this->post('/architect/api/blueprints/submit', array_merge([
            '_blueprint' => 'user',
            '_state' => 'add',
            '_id' => null,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => Str::random(8),
            'api_token' => Str::random(80),
        ], $params));
    }
}
