<?php

declare(strict_types=1);

namespace JPeters\Architect\Console\Concerns;

trait UpdatesComposer
{
    protected function updateComposer(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        if (!isset($composer['repositories'])) {
            $composer['repositories'] = [];
        }

        $composer['repositories'][] = [
            'type' => 'path',
            'url' => './'.$this->relativePath,
        ];

        $composer['require'][$this->packageNamespace] = 'dev-master';
        ksort($composer['require']);

        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
