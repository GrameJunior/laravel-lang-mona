<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Concerns;

use function Laravel\Prompts\intro;

/** @mixin \LaravelLang\Mona\Commands\Cldr\CldrCommand */
trait HasNative
{
    public function __invoke(): int
    {
        intro($this->commandName());

        $this->handle('_native');

        return self::SUCCESS;
    }
}
