<?php

declare(strict_types=1);

use Tests\Fixtures\ProjectNamespace;

arch()
    ->preset()
    ->security();

arch('suspicious characters')
    ->expect(ProjectNamespace::All)
    ->not->toHaveSuspiciousCharacters();
