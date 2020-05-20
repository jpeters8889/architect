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

class MakePageCommand extends Command
{
    protected $signature = 'architect:createPage {page}';

    protected $description = 'Make a custom Architect Page';

    /** @var string */
    private $packageNamespace;

    /** @var string */
    private $packageName;

    /** @var string */
    private $pagePath;

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
        $this->packageNamespace = $this->argument('page');
        $this->packageName = Str::kebab(Arr::last(explode('/', $this->packageNamespace)));
        $this->pagePath = base_path($this->relativePath = 'architect/pages/' . $this->packageName);

        // make architect/pages folder in root if it doesnt exist
        $this->info('Creating architect/pages folder');
        $this->makeDirectory(base_path('architect/pages'));

        // make page folder using tail part of page name
        $this->info('Creating ' . $this->packageName . ' folder');
        $this->makeDirectory($this->pagePath);

        // copy page stubs
        $this->info('Copying page skeleton');
        $this->filesystem->copyDirectory(__DIR__ . '/Stubs/page', $this->pagePath);

        // update place holders in the files
        $this->info('Configuring page skeleton');
        $this->configurePageSkeleton();

        // read and update main composer.json to add the new page
        $this->info('Updating Composer File');
        $this->updateComposer();

        // composer install locally
        if ($this->confirm('Do you want to install the and update your composer autoloader for the new page?')) {
            $this->executeCommand('composer require ' . $this->packageNamespace, base_path());
        }

        // install page npm dependencies
        if ($this->confirm('Do you want to install your pages NPM dependencies?')) {
            $this->executeCommand('npm install', $this->pagePath);
        }

        // build app
        if ($this->confirm('Do you want to build the provided page skeleton?')) {
            $this->executeCommand('npm run dev', $this->pagePath);
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

    protected function configurePageSkeleton(): void
    {
        (new Collection($this->filesystem->allFiles($this->pagePath)))
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
