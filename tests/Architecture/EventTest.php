<?php

declare(strict_types=1);

use Illuminate\Foundation\Events\Dispatchable;

arch('protect')
    ->expect([
        'LaravelLang\Models\Events',
        'LaravelLang\Routes\Events',
    ])
    ->toBeFinal()
    ->toBeReadonly();

arch('dispatchable')
    ->expect([
        'LaravelLang\Models\Events',
        'LaravelLang\Routes\Events',
    ])
    ->not->toUseTrait(Dispatchable::class);

arch('naming')
    ->expect([
        'LaravelLang\Models\Events',
        'LaravelLang\Routes\Events',
    ])
    ->toHaveSuffix('Event');
