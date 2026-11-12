<?php

declare(strict_types=1);

use function Orchestra\Testbench\workbench_path;

return [
    'models' => [
        'directory' => [
            app_path('Models'),
            workbench_path('app/Models'),
        ],
    ],
];
