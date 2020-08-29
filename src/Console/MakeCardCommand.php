<?php

declare(strict_types=1);

namespace JPeters\Architect\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use JPeters\Architect\Console\Concerns\UpdatesComposer;
use JPeters\Architect\Console\Concerns\ExecutesCommands;
use JPeters\Architect\Console\Concerns\ArchitectCreateWizard;
use JPeters\Architect\Console\Concerns\CreatesProjectSkeleton;

class MakeCardCommand extends Command
{
    use ArchitectCreateWizard;
    use CreatesProjectSkeleton;
    use ExecutesCommands;
    use UpdatesComposer;

    protected $signature = 'architect:createCard {card}';

    protected $description = 'Make a custom Architect Card';

    protected string $packageNamespace;

    protected string $packageName;

    protected string $cardPath;

    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle(): void
    {
        $this->runWizard('card');
    }

    protected function makeDirectory($directory): void
    {
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0775, true);
        }
    }
}
