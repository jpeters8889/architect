<?php

declare(strict_types=1);

namespace JPeters\Architect\Console\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait ArchitectCreateWizard
{
    protected function runWizard(string $name): void
    {
        $this->packageNamespace = (string) $this->argument($name);
        $this->packageName = Str::kebab(Arr::last(explode('/', $this->packageNamespace)));
        $path = base_path($this->relativePath = "architect/{$name}s/{$this->packageName}");

        // make architect/plans folder in root if it doesnt exist
        $this->info("Creating architect/{$name} folder");
        $this->makeDirectory(base_path("architect/{$name}"));

        // make plan folder using tail part of plan name
        $this->info("Creating {$this->packageName} folder");
        $this->makeDirectory($path);

        // copy plan stubs
        $this->info('Copying plan skeleton');
        $this->filesystem->copyDirectory(__DIR__.'/../Stubs/'.$name, $path);

        // update place holders in the files
        $this->info("Configuring {$name} skeleton");
        $this->configurePlanSkeleton($path);

        // read and update main composer.json to add the new plan
        $this->info('Updating Composer File');
        $this->updateComposer();

        // composer install locally
        if ($this->confirm("Do you want to install the and update your composer autoloader for the new {$name}?")) {
            $this->executeCommand('composer require '.$this->packageNamespace, base_path());
        }

        // install plan npm dependencies
        if ($this->confirm("Do you want to install your {$name}s NPM dependencies?")) {
            $this->executeCommand('npm install', $path);
        }

        // build app
        if ($this->confirm("Do you want to build the provided {$name} skeleton?")) {
            $this->executeCommand('npm run dev', $path);
        }
    }
}
