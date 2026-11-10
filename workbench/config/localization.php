<?php

declare(strict_types=1);

use Illuminate\Support\Facades\ParallelTesting;
use LaravelLang\LocaleList\Locale;
use Workbench\App\Constants\FakeLocale;

return [
    'aliases' => [
        Locale::French->value  => FakeLocale::DefaultAlias,
        Locale::Italian->value => FakeLocale::ItalianAlias,
    ],

    'models' => [
        'helpers' => base_path('vendor/_laravel_lang/' . (ParallelTesting::token() ?: '0')),
    ],
];
