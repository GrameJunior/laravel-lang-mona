<?php

declare(strict_types=1);

use Tests\Fixtures\ProjectNamespace;

arch('dev functions')
    ->expect(ProjectNamespace::All)
    ->not->toUse([
        'dd',
        'die',
        'dump',
        'echo',
        'exit',
        'print_r',
        'printf',
        'var_dump',
    ]);
