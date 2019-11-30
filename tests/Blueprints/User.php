<?php

namespace JPeters\Architect\Tests\Blueprints;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Controls\Textfield;

class User extends Blueprint
{
    public function model(): string
    {
        return \JPeters\Architect\Tests\Models\User::class;
    }

    public function plans(): array
    {
        return [
            new Textfield('name'),

            new Textfield('email'),

            new Textfield('password'),

            new Textfield('api_token'),
        ];
    }
}
