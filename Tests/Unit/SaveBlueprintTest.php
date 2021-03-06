<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use Illuminate\Support\Str;
use Illuminate\Routing\Redirector;
use Illuminate\Foundation\Testing\WithFaker;
use JPeters\Architect\Blueprints\SaveBlueprint;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User;
use JPeters\Architect\Http\Requests\BlueprintSubmitRequest;
use JPeters\Architect\TestHelpers\Laravel\Models\User as UserModel;

class SaveBlueprintTest extends ArchitectTestCase
{
    use WithFaker;

    /**
     * @var BlueprintSubmitRequest
     */
    private $request;

    /**
     * @var SaveBlueprint
     */
    private $saveBlueprint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeFaker('en_GB');

        $this->makeRequest();
    }

    /** @test */
    public function itSavesTheBlueprintWhenAddingData()
    {
        $this->assertEmpty(UserModel::query()->get());

        $this->saveBlueprint->saveBlueprint();

        $this->assertCount(1, UserModel::query()->get());

        $user = UserModel::query()->first();

        foreach ($this->request->input() as $column => $value) {
            if (Str::startsWith($column, '_')) {
                continue;
            }

            $this->assertEquals($value, $user->$column);
        }
    }

    /** @test */
    public function itSavesTheBlueprintWhenUpdatingData()
    {
        $originalUser = factory(UserModel::class)->create();

        $this->makeRequest(['_state' => 'update', '_id' => $originalUser->id]);

        $this->saveBlueprint->saveBlueprint();

        $this->assertCount(1, UserModel::query()->get());

        $user = UserModel::query()->first();

        foreach ($this->request->input() as $column => $value) {
            if (Str::startsWith($column, '_')) {
                continue;
            }

            $this->assertEquals($value, $user->$column);
            $this->assertNotEquals($originalUser->$column, $user->$column);
        }
    }

    protected function makeRequest($params = []): void
    {
        $this->request = new BlueprintSubmitRequest(array_merge([
            '_blueprint' => 'user',
            '_state' => 'add',
            '_id' => null,
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => Str::random(8),
            'api_token' => Str::random(80),
        ], $params));

        $this->request
            ->setContainer(app())
            ->setRedirector(resolve(Redirector::class))
            ->validateResolved();

        $this->saveBlueprint = new SaveBlueprint(new User(), $this->request);
    }
}
