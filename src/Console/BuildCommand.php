<?php

namespace JPeters\Architect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class BuildCommand extends Command
{
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

    public function handle()
    {
        foreach ($this->directories as $directory) {
            $this->info('Checking for custom Architect ' . $name = Str::of($directory)->pluralStudly());

            if (!$this->filesystem->exists($basePath = base_path('architect/' . $directory))) {
                $this->info("No {$name} found in your app");
                continue;
            }

            foreach ($this->filesystem->directories($basePath) as $path) {
                $buildName = Str::pluralStudly(Arr::last(explode('/', $path)));
                
                $this->info("Building {$buildName} {$name}");
                $this->executeCommand("npm run {$this->argument('env')}", $path);
            }
        }
    }

    protected function executeCommand($command, $path)
    {
        (new Process(explode(' ', $command), $path))->setTimeout(null)->run(function ($type, $line) {
            $this->output->write($line);
        });
    }
}
