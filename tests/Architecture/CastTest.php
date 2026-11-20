<?php

declare(strict_types=1);

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Tests\Fixtures\ProjectNamespace;

arch('protect')
    ->expect(ProjectNamespace::All)
    ->implementing(CastsAttributes::class)
    ->toBeFinal()
    ->ignoring('LaravelLang\Routes\localizedRoute');

arch('naming')
    ->expect(ProjectNamespace::All)
    ->implementing(CastsAttributes::class)
    ->toHaveSuffix('Cast')
    ->ignoring('LaravelLang\Routes\localizedRoute');
