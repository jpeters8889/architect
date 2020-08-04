<?php

declare(strict_types=1);

namespace JPeters\Architect\Console\Concerns;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Symfony\Component\Finder\SplFileInfo;

trait CreatesProjectSkeleton
{
    protected function configurePlanSkeleton($path): void
    {
        (new Collection($this->filesystem->allFiles($path)))
            ->each(function (SplFileInfo $file) {
                $contents = str_replace(
                    [
                        '{{package-namespace}}',
                        '{{app-namespace}}',
                        '{{package-name-namespace}}',
                        '{{package-name}}',
                        '{{package-namespace-dash}}',
                    ],
                    [
                        $this->packageNamespace,
                        Str::replaceLast('\\', '', Container::getInstance()->getNamespace()),
                        Str::studly($this->packageName),
                        Str::kebab($this->packageName),
                        str_replace('/', '-', $this->packageNamespace),
                    ],
                    $file->getContents()
                );

                file_put_contents($file->getRealPath(), $contents);
            });
    }
}
