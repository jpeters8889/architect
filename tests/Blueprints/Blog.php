<?php

namespace JPeters\Architect\Tests\Blueprints;

use JPeters\Architect\Blueprints\Blueprint;
use JPeters\Architect\Controls\Textarea;
use JPeters\Architect\Controls\Textfield;

class Blog extends Blueprint
{
    public function model(): string
    {
        return \JPeters\Architect\Tests\Models\Blog::class;
    }

    public function plans(): array
    {
        return [
            new Textfield('name'),

            new Textarea('body'),
        ];
    }
}
