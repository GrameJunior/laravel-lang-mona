<?php

declare(strict_types=1);

use Illuminate\Support\Facades\ParallelTesting;

use function Orchestra\Testbench\workbench_path;

return [
    'directories' => [
        app_path('Models'),
        workbench_path('app/Models'),
    ],

    'helpers' => app_path('vendor/_laravel_lang/' . ParallelTesting::token()),
];
