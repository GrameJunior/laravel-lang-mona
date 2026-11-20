<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Facade;
use Tests\Fixtures\ProjectNamespace;

arch('protect')
    ->expect(ProjectNamespace::All)
    ->classes()
    ->extending(Facade::class)
    ->toBeFinal();
