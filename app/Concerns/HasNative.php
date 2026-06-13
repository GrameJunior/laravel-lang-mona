<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Concerns;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\intro;

/** @mixin \LaravelLang\Mona\Commands\Cldr\CldrCommand */
trait HasNative
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        intro($this->commandName());

        $this->handle('_native');

        return self::SUCCESS;
    }
}
