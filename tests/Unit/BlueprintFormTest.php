<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Blueprints\BlueprintFormExtractor;
use JPeters\Architect\Plans\Plan;
use JPeters\Architect\Tests\ArchitectTest;
use JPeters\Architect\Tests\Blueprints\User;
use JPeters\Architect\Tests\Models\User as UserModel;

class BlueprintFormTest extends ArchitectTest
{
    /**
     * @var BlueprintFormExtractor
     */
    private $form;

    protected function setUp(): void
    {
        parent::setUp();

        $this->form = new BlueprintFormExtractor(new User());
    }

    /** @test */
    public function it_creates_a_list_with_the_correct_keys()
    {
        $keys = ['plans', 'meta', 'vue-suffix'];

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $this->form->make());
        }
    }

    /** @test */
    public function it_contains_the_meta_information()
    {
        $this->assertEquals(['title' => 'Users'], $this->form->make()['meta']);
    }

    /** @test */
    public function it_shows_the_vue_suffix()
    {
        $this->assertEquals('list', $this->form->make()['vue-suffix']);
    }

    /** @test */
    public function it_returns_an_array_of_plans()
    {
        $this->assertIsArray($this->form->make()['plans']);
    }

    /** @test */
    public function it_displays_the_plans_in_the_correct_format()
    {
        $keys = ['component', 'label', 'metas', 'name', 'value'];

        foreach ($this->form->make()['plans'] as $plan) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $plan);
            }
        }
    }

    /** @test */
    public function it_doesnt_display_a_plan_that_is_hidden_from_forms()
    {
        $this->assertNotContains('updates_at', $this->form->make()['plans']);
    }

    /** @test */
    public function it_displays_all_of_the_plans_in_the_blueprint()
    {
        $blueprint = new User();
        $plans = $this->form->make()['plans'];

        foreach ($blueprint->plans() as $index => $currentPlan) {
            /** @var Plan $currentPlan */
            if (! $currentPlan->isAvailableOnForm()) {
                continue;
            }

            $plan = $plans[$index];

            $this->assertEquals($currentPlan->vuePrefix().'-form', $plan['component']);
            $this->assertEquals($currentPlan->getLabel(), $plan['label']);
            $this->assertEquals($currentPlan->getColumn(), $plan['name']);
        }
    }

    /** @test */
    public function it_loads_plans_with_a_value_when_loading_the_edit_form()
    {
        /** @var UserModel $userModel */
        $userModel = factory(UserModel::class)->create();

        $this->form->getValuesFrom($userModel->id)->make();

        $blueprint = new User();
        $plans = $this->form->make()['plans'];

        foreach ($blueprint->plans() as $index => $currentPlan) {
            /** @var Plan $currentPlan */
            if (! $currentPlan->isAvailableOnForm()) {
                continue;
            }

            $plan = $plans[$index];

            $this->assertEquals($userModel->{$currentPlan->getColumn()}, $plan['value']);
        }
    }
}
