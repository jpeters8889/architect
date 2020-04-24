<?php

namespace JPeters\Architect\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JPeters\Architect\Plans\Boolean;
use JPeters\Architect\Plans\Group;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Plans\Textfield;
use JPeters\Architect\Tests\Abstracts\PlanTestCase;
use JPeters\Architect\Tests\Laravel\Models\Blog;
use JPeters\Architect\Tests\Laravel\Models\User;

class GroupPlanTest extends PlanTestCase
{
    use WithFaker;

    /** @var Group */
    protected $plan;

    /**
     * @var array
     */
    private $setPlans;

    protected function setUp(): void
    {
        parent::setUp();

        $this->makeFaker('en_GB');

        $this->plan->plans($this->setPlans = [
            new Textfield('name'),
            new Textfield('password'),
        ]);
    }

    public function getPlan()
    {
        return Group::class;
    }

    public function getColumnName()
    {
        return 'Group';
    }

    /** @test */
    public function it_can_have_plans_set()
    {
        $this->assertInstanceOf(Collection::class, $this->plan->getPlans());

        $this->plan->getPlans()->each(function ($plan, $index) {
            $this->assertEquals($this->setPlans[$index]->getLabel(), $plan['label']);
            $this->assertEquals($this->setPlans[$index]->getColumn(), $plan['name']);
            $this->assertEquals($this->setPlans[$index]->vuePrefix() . '-form', $plan['component']);
            $this->assertEquals($this->setPlans[$index]->getMetas(), $plan['metas']);
            $this->assertNull($plan['value']);
        });
    }

    /** @test */
    public function it_returns_plans_in_the_metas()
    {
        $this->assertArrayHasKey('plans', $this->plan->getMetas());
    }

    /** @test */
    public function it_returns_each_plans_value_when_the_current_model_is_set()
    {
        $user = factory(User::class)->create();

        $this->plan->getCurrentValue($user);

        $this->plan->getPlans()->each(function ($plan, $index) use ($user) {
            $this->assertEquals($user->{$this->setPlans[$index]->getColumn()}, $plan['value']);
        });
    }

    /** @test */
    public function it_returns_each_plans_value_from_a_relationship()
    {
        /** @var Blog $blog */
        $blog = factory(Blog::class)->create();
        $blog->attributes()->create([
            'first' => 'foo',
            'second' => 'bar',
        ]);

        $plans = [
            new Textfield('first'),
            new Textfield('second'),
        ];

        $this->plan->plans($plans)->setRelationship('attributes');

        $this->plan->getCurrentValue($blog);

        $this->plan->getPlans()->each(function ($plan, $index) use ($blog, $plans) {
            $this->assertEquals($blog->attributes()->first()->{$plans[$index]->getColumn()}, $plan['value']);
        });
    }

    /** @test */
    public function it_returns_each_value_from_a_pivot_relationship()
    {
        DB::table('blog_tags')
            ->insert([
                [
                    'tag' => 'First Tag',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'tag' => 'First Tag',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

        /** @var Blog $blog */
        $blog = factory(Blog::class)->create();
        $blog->tags()->attach([2]);

        $plans = [
            new Boolean(1, 'First Tag'),
            new Boolean(2, 'Second Tag'),
        ];

        $this->plan->plans($plans)->setPivotRelationship('tags');

        $this->plan->getCurrentValue($blog);

        $this->assertEquals('0', $this->plan->getPlans()[0]['value']);
        $this->assertEquals('1', $this->plan->getPlans()[1]['value']);
    }

    /** @test */
    public function it_can_be_set_to_wrap_the_columns()
    {
        $this->assertArrayHasKey('wrap', $this->plan->getMetas());
        $this->assertFalse($this->plan->getMetas()['wrap']);

        $this->plan->wrapPlans();

        $this->assertTrue($this->plan->getMetas()['wrap']);
    }

    /** @test */
    public function it_updates_the_model()
    {
        $values = [];

        foreach ($this->setPlans as $plan) {
            /** @var Plan $plan */
            $values[$plan->getColumn()] = $this->faker->word;
        }

        $user = new User();
        $user->email = 'foo';

        $this->plan->handleUpdate($user, $this->plan->getColumn(), json_encode($values));

        $user->save();

        unset($user);

        $user = User::query()->first();

        foreach ($this->setPlans as $plan) {
            /** @var Plan $plan */
            $this->assertEquals(
                $values[$plan->getColumn()],
                $user->{$plan->getColumn()}
            );
        }
    }

    /** @test */
    public function it_updates_the_model_when_a_relationship_is_set()
    {
        /** @var Blog $blog */
        $blog = factory(Blog::class)->create();

        $plans = [
            new Textfield('first'),
            new Textfield('second'),
        ];

        $this->plan->plans($plans)->setRelationship('attributes');

        $values = [];

        foreach ($plans as $plan) {
            /** @var Plan $plan */
            $values[$plan->getColumn()] = $this->faker->word;
        }

        $this->plan->handleUpdate($blog, $this->plan->getColumn(), json_encode($values));

        $additional = $blog->fresh()->attributes()->first();

        foreach ($plans as $plan) {
            /** @var Plan $plan */
            $this->assertEquals(
                $values[$plan->getColumn()],
                $additional->{$plan->getColumn()}
            );
        }
    }

    /** @test */
    public function it_will_attach_values_when_using_a_pivot_relationship()
    {
        DB::table('blog_tags')
            ->insert([
                [
                    'tag' => 'First Tag',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'tag' => 'First Tag',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

        /** @var Blog $blog */
        $blog = factory(Blog::class)->create();

        $plans = [
            new Boolean(1, 'First Tag'),
            new Boolean(2, 'Second Tag'),
        ];

        $this->plan->plans($plans)->setPivotRelationship('tags');

        $values = [
            1 => false,
            2 => true,
        ];

        $this->plan->handleUpdate($blog, $this->plan->getColumn(), json_encode($values));

        $this->assertEquals(1, DB::table('blog_assigned_tags')->count());

        $tag = DB::table('blog_assigned_tags')->first();

        $this->assertEquals($blog->id, $tag->blog_id);
        $this->assertEquals(2, $tag->tag_id);
    }
}
