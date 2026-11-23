<?php

declare(strict_types=1);

use Tests\Fixtures\ProjectNamespace;

arch('naming')
    ->expect(ProjectNamespace::All)
    ->interfaces()
    ->not->toHaveSuffix('Contract')
    ->not->toHaveSuffix('Interface');
