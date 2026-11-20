<?php

declare(strict_types=1);

use LaravelLang\Models\Generators\Generator;
use Tests\Fixtures\ProjectNamespace;

arch('naming')
    ->expect(ProjectNamespace::All)
    ->extending(Generator::class)
    ->toHaveSuffix('Generator');
