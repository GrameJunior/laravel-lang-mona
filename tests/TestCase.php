<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Translation\TranslationServiceProvider;
use LaravelLang\JsonFallback\TranslationServiceProvider as FixedTranslationServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTests;

class TestCase extends BaseTests
{
    use WithWorkbench;
    use RefreshDatabase;

    protected function overrideApplicationProviders($app): array
    {
        return [
            TranslationServiceProvider::class => FixedTranslationServiceProvider::class,
        ];
    }
}
