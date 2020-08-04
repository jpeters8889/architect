<?php

declare(strict_types=1);

namespace JPeters\Architect\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Container\Container;

class InstallCommand extends Command
{
    protected $signature = 'architect:install';

    protected $description = 'Install Architect';

    public function handle(): void
    {
        $this->comment('Publishing Architect Assets');
        $this->callSilent('architect:publish');

        $this->info('Publishing Service Provider');
        $this->comment('Publishing Architect Service Provider');
        $this->callSilent('vendor:publish', ['--tag' => 'architect-provider']);

        $this->info('Registering Service Provider');
        $this->registerServiceProvider();

        $this->writeAppNamespace();
    }

    protected function getAppNamespace(): string
    {
        return Container::getInstance()->getNamespace();
    }

    protected function registerServiceProvider(): voisd
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());
        $appConfigFile = config_path('app.php');

        file_put_contents($appConfigFile, str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".
            PHP_EOL.
            "        {$namespace}\Providers\ArchitectServiceProvider::class,".PHP_EOL,
            file_get_contents($appConfigFile)
        ));
    }

    protected function writeAppNamespace(): void
    {
        $namespace = $this->getAppNamespace();

        $this->writeAppNamespaceOn(app_path('Providers/ArchitectServiceProvider.php'), $namespace);
    }

    protected function writeAppNamespaceOn(string $file, string $namespace): void
    {
        file_put_contents($file, str_replace(
            'App\\',
            $namespace,
            file_get_contents($file)
        ));
    }
}
