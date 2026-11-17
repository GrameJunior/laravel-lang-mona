<?php

declare(strict_types=1);

namespace Workbench\App\Collectors;

use LaravelLang\Publisher\Collectors\PluginCollector;

use function Orchestra\Testbench\workbench_path;

class FakePluginCollector extends PluginCollector
{
    protected function settings(): array
    {
        return [
            workbench_path('package/laravel_lang.json'),
        ];
    }
}
