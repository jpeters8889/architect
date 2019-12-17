<?php

namespace JPeters\Architect\Console;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Process\Process;

class MakePlanCommand extends Command
{
    protected $signature = 'architect:createPlan {plan}';

    protected $description = 'Make a custom Architect Plan';

    /** @var string */
    private $packageNamespace;

    /** @var string */
    private $packageName;

    /** @var string */
    private $planPath;

    /** @var Factory */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $this->packageNamespace = $this->argument('plan');
        $this->packageName = Str::kebab(Arr::last(explode('/', $this->packageNamespace)));
        $this->planPath = base_path('architectPlans/' . $this->packageName);

        // make architectPlans folder in root if it doesnt exist
        $this->info('Creating architectPlans folder');
        $this->makeDirectory(base_path('architectPlans'));

        // make plan folder using tail part of plan name
        $this->info('Creating ' . $this->packageName . ' folder');
        $this->makeDirectory($this->planPath);

        // copy plan stubs
        $this->info('Copying plan skeleton');
        $this->filesystem->copyDirectory(__DIR__ . '/Stubs/plan', $this->planPath);

        // update place holders in the files
        $this->info('Configuring plan skeleton');
        $this->configurePlanSkeleton();

        // read and update main composer.json to add the new plan
        $this->info('Updating Composer File');
        $this->updateComposer();

        // composer install locally
        if ($this->confirm('Do you want to install the and update your composer autoloader for the new plan?')) {
            $this->executeCommand('composer install', base_path());
        }

        // install plan npm dependencies
        if ($this->confirm('Do you want to install your plans NPM dependencies?')) {
            $this->executeCommand('npm set progress=false && npm install', $this->planPath);
        }

        // build app
        if ($this->confirm('Do you want to build the provided plan skeleton?')) {
            $this->executeCommand('npm run dev', $this->planPath);
        }
    }

    protected function executeCommand($command, $path)
    {
        (new Process($command, $path))->setTimeout(null)->run(function ($type, $line) {
            $this->output->write($line);
        });
    }

    protected function makeDirectory($directory)
    {
        if (! $this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory);
        }
    }

    protected function configurePlanSkeleton(): void
    {
        (new Collection($this->filesystem->allFiles($this->planPath)))
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

    protected function updateComposer(): void
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        if (! isset($composer['repositories'])) {
            $composer['repositories'] = [];
        }

        $composer['repositories'][] = [
            'type' => 'path',
            'url' => str_replace('/', '\\', $this->planPath),
        ];

        $composer['require'][$this->packageNamespace] = 'dev-master';
        ksort($composer['require']);

        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
