<?php

namespace {{app-namespace}}\Architect\Pages\{{package-name-namespace}};

use JPeters\Architect\Pages\Page as ArchitectPage;

class Page implements ArchitectPage
{
    public function vueComponent(): string
    {
        return '{{package-name}}-page';
    }
}
