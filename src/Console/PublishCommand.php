<?php

declare(strict_types=1);

namespace JPeters\Architect\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'architect:publish {--force : Overwrite any existing files}';

    protected $description = 'Publish the Architect resources';

    public function handle(): void
    {
        $this->info('Publishing Architect Configuration File');
        $this->call('vendor:publish', [
            '--tag' => 'architect-config',
            '--force' => $this->option('force'),
        ]);

        $this->info('Publishing Architect Assets');
        $this->call('vendor:publish', [
            '--tag' => 'architect-assets',
            '--force' => true,
        ]);

        $this->info('Clearing View Cache');
        $this->call('view:clear');
    }
}
