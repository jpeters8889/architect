<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Feature;

use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Models\Variant as VariantModel;
use JPeters\Architect\TestHelpers\Traits\LogsInUsers;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\Variant as VariantBlueprint;

class BlueprintSubmitWithBulkPlanTest extends ArchitectTestCase
{
    use LogsInUsers;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->architect->registerBlueprint(VariantBlueprint::class);

        $this->logIn();
        $this->makeFaker('en_GB');
    }

    /** @test */
    public function itCreatesASingleRecord(): void
    {
        $this->assertEmpty(VariantModel::all());

        $this->post('/architect/api/blueprints/submit', [
            '_blueprint' => 'variant',
            '_state' => 'add',
            '_id' => null,
            'title' => 'Foobar',
            'description' => $this->faker->safeEmail,
            'options' => [
                [
                    'option' => $this->faker->word,
                    'price' => $this->faker->numberBetween(100, 1000),
                ],
            ],
        ])->assertStatus(201);

        $this->assertNotEmpty(VariantModel::all());
        $this->assertCount(1, VariantModel::all());
    }

    /** @test */
    public function itCreatesMultipleRecordsWithStaticAndMixedData(): void
    {
        $this->assertEmpty(VariantModel::all());

        $this->post('/architect/api/blueprints/submit', [
            '_blueprint' => 'variant',
            '_state' => 'add',
            '_id' => null,
            'title' => 'Static Title',
            'description' => 'Static Description',
            'options' => [
                [
                    'option' => 'option 1',
                    'price' => 1000,
                ],
                [
                    'option' => 'option 2',
                    'price' => 2000,
                ],
            ],
        ])->assertStatus(201);

        $this->assertNotEmpty(VariantModel::all());
        $this->assertCount(2, VariantModel::all());

        $variants = VariantModel::all();

        $this->assertEquals('Static Title', $variants[0]->title);
        $this->assertEquals('Static Title', $variants[1]->title);
        $this->assertEquals('Static Description', $variants[0]->description);
        $this->assertEquals('Static Description', $variants[1]->description);

        $this->assertEquals('option 1', $variants[0]->option);
        $this->assertEquals('1000', $variants[0]->price);
        $this->assertEquals('option 2', $variants[1]->option);
        $this->assertEquals('2000', $variants[1]->price);
    }

    /** @test */
    public function itUpdatesASingleRecordInAUpdateRequest()
    {
        $variant = VariantModel::query()->create([
            'title' => 'Title',
            'description' => 'Description',
            'option' => 'Option',
            'price' => 1000,
        ]);

        $this->post('/architect/api/blueprints/submit', [
            '_blueprint' => 'variant',
            '_state' => 'update',
            '_id' => $variant->id,
            'title' => 'Title Updated',
            'description' => 'Description Updated',
            'options' => [
                [
                    'option' => 'Option Updated',
                    'price' => 2000,
                ]
            ],
        ])->assertStatus(201);

        $variant->refresh();

        $this->assertEquals('Title Updated', $variant->title);
        $this->assertEquals('Description Updated', $variant->description);
        $this->assertEquals('Option Updated', $variant->option);
        $this->assertEquals('2000', $variant->price);
    }
}
