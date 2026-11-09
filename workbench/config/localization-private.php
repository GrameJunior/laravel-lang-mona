<?php

declare(strict_types=1);

use LaravelLang\Config\Helpers\Path;

use function Orchestra\Testbench\workbench_path;

return [
    'models' => [
        'directory' => [
            Path::app(),
            workbench_path('app/Models'),
        ],
    ],
];
