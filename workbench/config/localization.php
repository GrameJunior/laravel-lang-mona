<?php

declare(strict_types=1);

use Illuminate\Support\Facades\ParallelTesting;
use LaravelLang\LocaleList\Locale;

return [
    'aliases' => [
        Locale::French->value  => 'fr_alias',
        Locale::Italian->value => 'it_alias',
    ],

    'models' => [
        'helpers' => base_path('vendor/_laravel_lang/' . (ParallelTesting::token() ?: '0')),
    ],
];
