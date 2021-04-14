<?php

declare(strict_types=1);

namespace JPeters\Architect\Tests\Unit;

use Carbon\Carbon;
use JPeters\Architect\Plans\Plan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use JPeters\Architect\Plans\Group;
use JPeters\Architect\Plans\Boolean;
use JPeters\Architect\Plans\Textfield;
use Illuminate\Foundation\Testing\WithFaker;
use JPeters\Architect\TestHelpers\Laravel\Models\Blog;
use JPeters\Architect\TestHelpers\Laravel\Models\User;
use JPeters\Architect\TestHelpers\Abstracts\PlanTestCase;

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
    public function itCanHavePlansSet()
    {
        $this->assertInstanceOf(Collection::class, $this->plan->getPlans());

        $this->plan->getPlans()->each(function ($plan, $index) {
            $this->assertEquals($this->setPlans[$index]->getLabel(), $plan['label']);
            $this->assertEquals($this->setPlans[$index]->getColumn(), $plan['name']);
            $this->assertEquals($this->setPlans[$index]->vuePrefix().'-form', $plan['component']);
            $this->assertEquals($this->setPlans[$index]->getMetas(), $plan['metas']);
            $this->assertNull($plan['value']);
        });
    }

    /** @test */
    public function itReturnsPlansInTheMetas()
    {
        $this->assertArrayHasKey('plans', $this->plan->getMetas());
    }

    /** @test */
    public function itReturnsEachPlansValueWhenTheCurrentModelIsSet()
    {
        $user = factory(User::class)->create();

        $this->plan->getCurrentValue($user);

        $this->plan->getPlans()->each(function ($plan, $index) use ($user) {
            $this->assertEquals($user->{$this->setPlans[$index]->getColumn()}, $plan['value']);
        });
    }

    /** @test */
    public function itReturnsEachPlansValueFromARelationship()
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
    public function itReturnsEachValueFromAPivotRelationship()
    {
        DB::table('blog_tags')
            ->insert([
                [
                    'tag' => 'First Tag',
                    'slug' => 'first-tag',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'tag' => 'Second Tag',
                    'slug' => 'second-tag',
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
    public function itCanBeSetToWrapTheColumns()
    {
        $this->assertArrayHasKey('wrap', $this->plan->getMetas());
        $this->assertFalse($this->plan->getMetas()['wrap']);

        $this->plan->wrapPlans();

        $this->assertTrue($this->plan->getMetas()['wrap']);
    }

    /** @test */
    public function itUpdatesTheModel()
    {
        $values = [];

        foreach ($this->setPlans as $plan) {
            /* @var Plan $plan */
            $values[$plan->getColumn()] = $this->faker->word;
        }

        $user = new User();
        $user->email = 'foo';

        $this->plan->handleUpdate($user, $this->plan->getColumn(), json_encode($values));

        $user->save();

        unset($user);

        $user = User::query()->first();

        foreach ($this->setPlans as $plan) {
            /* @var Plan $plan */
            $this->assertEquals(
                $values[$plan->getColumn()],
                $user->{$plan->getColumn()}
            );
        }
    }

    /** @test */
    public function itUpdatesTheModelWhenARelationshipIsSet()
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
            /* @var Plan $plan */
            $values[$plan->getColumn()] = $this->faker->word;
        }

        $this->plan->handleUpdate($blog, $this->plan->getColumn(), json_encode($values));

        $additional = $blog->fresh()->attributes()->first();

        foreach ($plans as $plan) {
            /* @var Plan $plan */
            $this->assertEquals(
                $values[$plan->getColumn()],
                $additional->{$plan->getColumn()}
            );
        }
    }

    /** @test */
    public function itWillAttachValuesWhenUsingAPivotRelationship()
    {
        DB::table('blog_tags')
            ->insert([
                [
                    'tag' => 'First Tag',
                    'slug' => 'first-tag',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'tag' => 'Second Tag',
                    'slug' => 'second-tag',
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
