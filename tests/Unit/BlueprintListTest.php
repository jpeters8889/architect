<?php

namespace JPeters\ArchitectTests\Unit;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use JPeters\Architect\Blueprints\BlueprintListExtractor;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Blueprints\User;
use JPeters\Architect\Tests\Models\User as UserModel;

class BlueprintListTest extends ArchitectTest
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

    /** @test */
    public function it_creates_a_list_with_the_correct_keys()
    {
        $keys = ['data', 'hiddenOnMobile', 'labels', 'meta', 'vue-suffix', 'vuePrefixes'];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $this->list);
        }
    }

    /** @test */
    public function it_shows_blueprint_plans_that_are_hidden_on_mobile()
    {
        $this->assertContains('api_token', $this->list['hiddenOnMobile']);
    }

    /** @test */
    public function it_doesnt_show_blueprint_labels_that_are_hidden_on_the_index()
    {
        $this->assertArrayNotHasKey('updated_at', $this->list['labels']);
    }

    /** @test */
    public function it_shows_the_blueprints_labels()
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
    public function it_contains_the_meta_information()
    {
        $this->assertEquals(['title' => 'Users'], $this->list['meta']);
    }

    /** @test */
    public function it_shows_the_vue_suffix()
    {
        $this->assertEquals('list', $this->list['vue-suffix']);
    }

    /** @test */
    public function it_shows_the_vue_prefixes()
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
    public function it_shows_the_data()
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
}
