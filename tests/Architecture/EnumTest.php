<?php

declare(strict_types=1);

use Tests\Fixtures\ProjectNamespace;

arch('enum suffix')
    ->expect(ProjectNamespace::All)
    ->enums()
    ->toHaveSuffix('Enum');

arch('all is string backed')
    ->expect(ProjectNamespace::All)
    ->enums()
    ->toBeStringBackedEnum();
