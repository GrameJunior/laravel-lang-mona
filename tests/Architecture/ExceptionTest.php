<?php

declare(strict_types=1);

arch('protect')
    ->expect('LaravelLang\Exceptions')
    ->toBeFinal();

arch()
    ->expect('LaravelLang\Exceptions')
    ->toExtend(Throwable::class);

arch()
    ->expect('LaravelLang\Exceptions')
    ->toHaveSuffix('Exception');
