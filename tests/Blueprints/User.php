<?php

namespace JPeters\Architect\Tests\Blueprints;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\DateTime;
use JPeters\Architect\Plans\Textfield;

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

            (new Textfield('api_token'))->hideFromIndexOnMobile(),

            (new DateTime('created_at'))->hideOnForms(),

            (new DateTime('updated_at'))->hideOnIndex()->hideOnForms(),
        ];
    }
}
