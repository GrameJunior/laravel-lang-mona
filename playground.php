<?php

declare(strict_types=1);

use DragonCode\Benchmark\Benchmark;

require __DIR__ . '/vendor/autoload.php';

Benchmark::make()
    ->warmup(2)
    ->compare(
        single  : static fn () => exec('php vendor/bin/pest'),
        parallel: static fn () => exec('php vendor/bin/pest --parallel'),
    )
    ->toConsole();
