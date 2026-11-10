<?php

declare(strict_types=1);

namespace Workbench\Package;

use LaravelLang\Publisher\Plugins\Provider;

use function Orchestra\Testbench\workbench_path;

class FakePluginProvider extends Provider
{
    protected array $plugins = [
        FakePlugin::class,
    ];

    protected function basePath(): string
    {
        return workbench_path('package');
    }
}
