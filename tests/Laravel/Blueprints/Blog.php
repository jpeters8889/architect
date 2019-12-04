<?php

namespace JPeters\Architect\Tests\Laravel\Blueprints;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Plans\Textarea;
use JPeters\Architect\Plans\Textfield;

class Blog extends Blueprint
{
    public function model(): string
    {
        return \JPeters\Architect\Tests\Laravel\Models\Blog::class;
    }

    public function plans(): array
    {
        return [
            new Textfield('name'),

            new Textarea('body'),
        ];
    }
}
