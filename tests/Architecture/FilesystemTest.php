<?php

declare(strict_types=1);

use LaravelLang\Filesystem\Processors\Processor;

arch()
    ->expect('LaravelLang\Filesystem')
    ->toBeFinal()
    ->ignoring([
        Processor::class,
    ]);
