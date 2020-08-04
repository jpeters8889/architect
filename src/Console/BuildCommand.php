<?php

declare(strict_types=1);

namespace JPeters\Architect\Console;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JPeters\Architect\Console\Concerns\ExecutesCommands;

class BuildCommand extends Command
{
    use ExecutesCommands;

    protected $signature = 'architect:build {env=prod}';

    protected $description = 'Build your custom Architect assets';

    protected Filesystem $filesystem;

    protected array $directories = [];

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->directories = [
            'cards',
            'pages',
            'plans',
        ];

        $this->filesystem = $filesystem;
    }

    public function handle(): void
    {
        foreach ($this->directories as $directory) {
            $this->info('Checking for custom Architect '.$name = Str::of($directory)->pluralStudly());

            if (!$this->filesystem->exists($basePath = base_path('architect/'.$directory))) {
                $this->info("No {$name} found in your app");
                continue;
            }

            $this->processDirectories($basePath, $name);
        }
    }

    public function processDirectories(string $basePath, string $name): void
    {
        foreach ($this->filesystem->directories($basePath) as $path) {
            $buildName = Str::pluralStudly(Arr::last(explode('/', $path)));

            $this->info("Building {$buildName} {$name}");

            if (!$this->filesystem->exists("{$basePath}/node_modules")) {
                $this->executeCommand('npm install', $path);
            }

            $this->executeCommand("npm run {$this->argument('env')}", $path);
            $this->executeCommand('rm -rf node_modules', $path);
        }
    }
}
