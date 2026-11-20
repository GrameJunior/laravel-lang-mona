<?php

declare(strict_types=1);

arch('protect')
    ->expect('LaravelLang\Models\Eloquent\Scopes')
    ->toBeFinal();

arch('naming')
    ->expect('LaravelLang\Models\Eloquent\Scopes')
    ->toHaveSuffix('Scope');
