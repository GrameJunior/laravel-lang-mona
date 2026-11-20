<?php

declare(strict_types=1);

use Tests\Fixtures\ProjectNamespace;

arch()
    ->preset()
    ->php();

arch('strict')
    ->expect(ProjectNamespace::All)
    ->toUseStrictTypes()
    ->toUseStrictEquality();

arch('cased')
    ->expect(ProjectNamespace::All)
    ->toBeCasedCorrectly()
    ->ignoring([
        'LaravelLang\Routes\localizedRoute',
    ]);

arch('sleep')
    ->expect(ProjectNamespace::All)
    ->expect([
        'sleep',
        'usleep',
    ])->not->toBeUsed();
