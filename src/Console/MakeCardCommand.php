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

class MakeCardCommand extends Command
{
    protected $signature = 'architect:createCard {card}';

    protected $description = 'Make a custom Architect Card';

    /** @var string */
    private $packageNamespace;

    /** @var string */
    private $packageName;

    /** @var string */
    private $cardPath;

    /** @var string */
    private $relativePath;

    /** @var Factory */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $this->packageNamespace = $this->argument('card');
        $this->packageName = Str::kebab(Arr::last(explode('/', $this->packageNamespace)));
        $this->cardPath = base_path($this->relativePath = 'architect/cards/' . $this->packageName);

        // make architect/cards folder in root if it doesnt exist
        $this->info('Creating architect/cards folder');
        $this->makeDirectory(base_path('architect/cards'));

        // make card folder using tail part of card name
        $this->info('Creating ' . $this->packageName . ' folder');
        $this->makeDirectory($this->cardPath);

        // copy card stubs
        $this->info('Copying card skeleton');
        $this->filesystem->copyDirectory(__DIR__ . '/Stubs/card', $this->cardPath);

        // update place holders in the files
        $this->info('Configuring card skeleton');
        $this->configureCardSkeleton();

        // read and update main composer.json to add the new card
        $this->info('Updating Composer File');
        $this->updateComposer();

        // composer install locally
        if ($this->confirm('Do you want to install the and update your composer autoloader for the new card?')) {
            $this->executeCommand('composer require ' . $this->packageNamespace, base_path());
        }

        // install card npm dependencies
        if ($this->confirm('Do you want to install your cards NPM dependencies?')) {
            $this->executeCommand('npm install', $this->cardPath);
        }

        // build app
        if ($this->confirm('Do you want to build the provided card skeleton?')) {
            $this->executeCommand('npm run dev', $this->cardPath);
        }
    }

    protected function executeCommand($command, $path)
    {
        (new Process(explode(' ', $command), $path))->setTimeout(null)->run(function ($type, $line) {
            $this->output->write($line);
        });
    }

    protected function makeDirectory($directory)
    {
        if (! $this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory);
        }
    }

    protected function configureCardSkeleton(): void
    {
        (new Collection($this->filesystem->allFiles($this->cardPath)))
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
            'url' => './' . $this->relativePath,
        ];

        $composer['require'][$this->packageNamespace] = 'dev-master';
        ksort($composer['require']);

        file_put_contents(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
