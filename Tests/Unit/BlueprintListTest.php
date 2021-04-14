<?php

declare(strict_types=1);

namespace JPeters\ArchitectTests\Unit;

use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Blueprints\Blueprint;
use Illuminate\Database\Eloquent\Collection;
use JPeters\Architect\TestHelpers\ArchitectTestCase;
use JPeters\Architect\TestHelpers\Laravel\Models\Blog;
use JPeters\Architect\Blueprints\BlueprintListExtractor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\User;
use JPeters\Architect\TestHelpers\Laravel\Models\User as UserModel;
use JPeters\Architect\TestHelpers\Laravel\Blueprints\Blog as BlogBlueprint;

class BlueprintListTest extends ArchitectTestCase
{
    /**
     * @var array
     */
    private $list;

    /** @var Collection */
    private $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = factory(UserModel::class, 15)->create();

        $this->list = (new BlueprintListExtractor(new User()))->make();
    }

    public function blueprintKeyDataProvider()
    {
        return [
            ['data'], ['hiddenOnMobile'], ['labels'], ['meta'], ['vue-suffix'],
            ['vuePrefixes'], ['card'], ['canEdit'], ['searchable'], ['filters'],
        ];
    }

    /**
     * @test
     * @dataProvider blueprintKeyDataProvider
     */
    public function itCreatesAListWithTheCorrectKeys($key)
    {
        $this->assertArrayHasKey($key, $this->list);
    }

    /** @test */
    public function itShowsBlueprintPlansThatAreHiddenOnMobile()
    {
        $this->assertContains('api_token', $this->list['hiddenOnMobile']);
    }

    /** @test */
    public function itDoesntShowBlueprintLabelsThatAreHiddenOnTheIndex()
    {
        $this->assertArrayNotHasKey('updated_at', $this->list['labels']);
    }

    /** @test */
    public function itShowsTheBlueprintsLabels()
    {
        $blueprint = new User();

        foreach ($blueprint->plans() as $plan) {
            /** @var Plan $plan */
            if (!$plan->isAvailableOnIndex()) {
                continue;
            }

            $this->assertArrayHasKey($plan->getColumn(), $this->list['labels']);
            $this->assertEquals($plan->getLabel(), $this->list['labels'][$plan->getColumn()]);
        }
    }

    /** @test */
    public function itShowsTheBlueprintsCard()
    {
        $card = (new User())->card();

        $this->assertEquals((new $card())->make(), $this->list['card']);

        $this->assertArrayHasKey('labels', $this->list['card']);
        $this->assertArrayHasKey('component', $this->list['card']);

        $this->assertIsArray($this->list['card']['labels']);
    }

    /** @test */
    public function itContainsTheMetaInformation()
    {
        $this->assertEquals(['title' => 'Users'], $this->list['meta']);
    }

    /** @test */
    public function itShowsTheVueSuffix()
    {
        $this->assertEquals('list', $this->list['vue-suffix']);
    }

    /** @test */
    public function itShowsTheVuePrefixes()
    {
        $blueprint = new User();

        foreach ($blueprint->plans() as $plan) {
            /** @var Plan $plan */
            if (!$plan->isAvailableOnIndex()) {
                continue;
            }

            $this->assertArrayHasKey($plan->getColumn(), $this->list['vuePrefixes']);
            $this->assertEquals($plan->vuePrefix(), $this->list['vuePrefixes'][$plan->getColumn()]);
        }
    }

    /** @test */
    public function itShowsTheCanEditOption()
    {
        $this->assertTrue($this->list['canEdit']);

        $this->list = (new BlueprintListExtractor(new class() extends Blueprint {
            public function model(): string
            {
                return Blog::class;
            }

            public function plans(): array
            {
                return [];
            }

            public function canEdit(): bool
            {
                return false;
            }
        }))->make();

        $this->assertFalse($this->list['canEdit']);
    }

    /** @test */
    public function itShowsTheData()
    {
        $this->assertInstanceOf(LengthAwarePaginator::class, $this->list['data']);

        $data = $this->list['data']->toArray();

        foreach ($data['data'] as $index => $item) {
            $user = $this->users[$index]->toArray();
            foreach ($this->list['labels'] as $column => $label) {
                $this->assertArrayHasKey($column, $user);
                $this->assertContains($user[$column], $item);
            }
        }
    }

    /** @test */
    public function itShowsWhetherTheBlueprintIsSearchable()
    {
        $this->assertTrue($this->list['searchable']);

        // Blogs are set as not searchable
        $blogBlueprint = (new BlueprintListExtractor(new BlogBlueprint()))->make();
        $this->assertFalse($blogBlueprint['searchable']);
    }

    /** @test */
    public function itReturnsACollectionOnTheFilterProperty()
    {
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $this->list['filters']);
    }

    /** @test */
    public function itReturnsAnEmptyCollectionWhenThereAreNoFilters()
    {
        $this->assertCount(0, $this->list['filters']);
    }

    /** @test */
    public function itReturnsAFormattedListOfFiltersWhenFiltersAreAvailable()
    {
        $blogBlueprint = (new BlueprintListExtractor($blog = new BlogBlueprint()))->make();
        $filters = $blog->filters();

        $this->assertNotCount(0, $blogBlueprint['filters']);

        foreach ($filters as $key => $filter) {
            $this->assertArrayHasKey($key, $blogBlueprint['filters']);
            $this->assertArrayHasKey('name', $blogBlueprint['filters'][$key]);
            $this->assertArrayHasKey('options', $blogBlueprint['filters'][$key]);

            $this->assertEquals($filter['name'], $blogBlueprint['filters'][$key]['name']);
            $this->assertIsIterable($blogBlueprint['filters'][$key]['options']);
        }
    }
}
