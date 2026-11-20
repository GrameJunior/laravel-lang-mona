<?php

declare(strict_types=1);

arch('naming')
    ->expect([
        'LaravelLang\Locales\Services',
        'LaravelLang\Models\Services',
        'LaravelLang\Routes\Services',
        'LaravelLang\Toolkit\Services',
    ])
    ->toHaveSuffix('Service');
