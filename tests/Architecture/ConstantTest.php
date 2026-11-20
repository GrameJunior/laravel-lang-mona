<?php

declare(strict_types=1);

arch('protect')
    ->expect('LaravelLang\Config\Constants')
    ->toBeFinal()
    ->toBeReadonly();

arch('extends nothing')
    ->expect('LaravelLang\Config\Constants')
    ->toExtendNothing()
    ->toImplementNothing()
    ->toUseNothing();

arch('methods')
    ->expect('LaravelLang\Config\Constants')
    ->not->toHavePublicMethods()
    ->not->toHaveProtectedMethods()
    ->not->toHavePrivateMethods();

arch('suffix')
    ->expect('LaravelLang\Config\Constants')
    ->not->toHaveSuffix('Constant');
