<?php

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use JPeters\Architect\Tests\ArchitectTestCase;
use JPeters\Architect\Tests\Laravel\Blueprints\User as UserBlueprint;
use JPeters\Architect\Tests\Laravel\Models\User;
use JPeters\Architect\Tests\Traits\LogsInUsers;

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
    public function it_rejects_requests_without_a_blueprint()
    {
        $this->makeRequest(['_blueprint' => null])->assertSessionHasErrors(['_blueprint']);
    }

    /** @test */
    public function it_rejects_requests_with_an_unknown_blueprint()
    {
        $this->makeRequest(['_blueprint' => 'foo'])
            ->assertStatus(404);
    }

    /** @test */
    public function it_rejects_requests_without_a_state()
    {
        $this->makeRequest(['_state' => null])->assertSessionHasErrors(['_state']);
    }

    /** @test */
    public function it_rejects_requests_without_an_invalid_state()
    {
        $this->makeRequest(['_state' => 'foo'])->assertSessionHasErrors(['_state']);
    }

    /** @test */
    public function it_rejects_requests_without_an_id_when_the_state_is_update()
    {
        $this->makeRequest(['_state' => 'update'])->assertSessionHasErrors(['_id']);
    }

    /** @test */
    public function it_creates_a_record_when_submitting_a_valid_add_request()
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
    public function it_updates_a_record_when_submitting_a_valid_update_request()
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
