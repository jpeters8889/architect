<?php

declare(strict_types=1);

namespace JPeters\Architect\Pages;

interface Page
{
    public function vueComponent(): string;
}
