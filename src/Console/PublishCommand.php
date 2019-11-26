<?php

namespace JPeters\Architect\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    protected $signature = 'architect:publish {--force : Overwrite any existing files}';

    protected $description = 'Publish the Architect resources';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'architect-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'architect-assets',
            '--force' => true,
        ]);

        $this->call('view:clear');
    }
}
