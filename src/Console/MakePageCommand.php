<?php

declare(strict_types=1);

namespace JPeters\Architect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JPeters\Architect\Console\Concerns\UpdatesComposer;
use JPeters\Architect\Console\Concerns\ExecutesCommands;
use JPeters\Architect\Console\Concerns\ArchitectCreateWizard;
use JPeters\Architect\Console\Concerns\CreatesProjectSkeleton;

class MakePageCommand extends Command
{
    use ArchitectCreateWizard;
    use CreatesProjectSkeleton;
    use ExecutesCommands;
    use UpdatesComposer;

    protected $signature = 'architect:createPage {page}';

    protected $description = 'Make a custom Architect Page';

    protected string $packageNamespace;

    protected string $packageName;

    protected string $pagePath;

    /** @var string */
    protected string $relativePath;

    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $this->runWizard('page', $this->pagePath);
    }

    protected function makeDirectory($directory)
    {
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory);
        }
    }
}
