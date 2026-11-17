<?php

declare(strict_types=1);

use LaravelLang\Publisher\Collectors\PluginCollector;
use Workbench\App\Collectors\FakePluginCollector;

function fakePluginCollector(): void
{
    app()->bind(PluginCollector::class, FakePluginCollector::class);
}
