<?php

declare(strict_types=1);

namespace JPeters\Architect\Console\Concerns;

use Symfony\Component\Process\Process;

trait ExecutesCommands
{
    protected function executeCommand(string $command, string $path): void
    {
        (new Process(explode(' ', $command), $path))->setTimeout(null)->run(function ($type, $line) {
            $this->output->write($line);
        });
    }
}
