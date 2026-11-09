<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;

dataset('locales filter', [
    'enabled' => [
        'enabled' => true,
        'count'   => 2,

        'locales' => [
            FakeLocale::Default,
            FakeLocale::Fallback,
        ],
    ],

    'disabled' => [
        'enabled' => false,
        'count'   => 3,

        'locales' => [
            FakeLocale::Default,
            FakeLocale::Fallback,
            FakeLocale::Custom,
        ],
    ],
]);

dataset('mixed values', [
    'filled string'     => [FakeLocale::Incorrect1, FakeLocale::Incorrect1],
    'empty string'      => ['', null],
    'string with space' => [' ', null],

    'integer above zero' => [123, 123],
    'integer as zero'    => [0, 0],

    'float above zero' => [1.23, 1.23],
    'float as zero'    => [0.0, 0.0],

    'boolean true'  => [true, true],
    'boolean false' => [false, false],

    'null' => [null, null],
]);
