<?php

namespace JPeters\Architect\Tests\Unit;

use JPeters\Architect\Blueprints\BlueprintFormExtractor;
use JPeters\Architect\Controls\Control;
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
        $keys = ['fields', 'meta', 'vue-suffix'];

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
    public function it_returns_an_array_of_fields()
    {
        $this->assertIsArray($this->form->make()['fields']);
    }

    /** @test */
    public function it_displays_the_fields_in_the_correct_format()
    {
        $keys = ['component', 'label', 'metas', 'name', 'value'];

        foreach ($this->form->make()['fields'] as $field) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $field);
            }
        }
    }

    /** @test */
    public function it_doesnt_display_a_field_that_is_hidden_from_forms()
    {
        $this->assertNotContains('updates_at', $this->form->make()['fields']);
    }

    /** @test */
    public function it_displays_all_of_the_fields_in_the_blueprint()
    {
        $blueprint = new User();
        $fields = $this->form->make()['fields'];

        foreach ($blueprint->plans() as $index => $plan) {
            /** @var Control $plan */
            if (! $plan->isAvailableOnForm()) {
                continue;
            }

            $field = $fields[$index];

            $this->assertEquals($plan->vuePrefix().'-form', $field['component']);
            $this->assertEquals($plan->getLabel(), $field['label']);
            $this->assertEquals($plan->getColumn(), $field['name']);
        }
    }

    /** @test */
    public function it_loads_controls_with_a_value_when_loading_the_edit_form()
    {
        /** @var UserModel $userModel */
        $userModel = factory(UserModel::class)->create();

        $this->form->getValuesFrom($userModel->id)->make();

        $blueprint = new User();
        $fields = $this->form->make()['fields'];

        foreach ($blueprint->plans() as $index => $plan) {
            /** @var Control $plan */
            if (! $plan->isAvailableOnForm()) {
                continue;
            }

            $field = $fields[$index];

            $this->assertEquals($userModel->{$plan->getColumn()}, $field['value']);
        }
    }
}
