<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Translation\TranslationServiceProvider;
use LaravelLang\JsonFallback\TranslationServiceProvider as FixedTranslationServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTests;

class TestCase extends BaseTests
{
    use WithWorkbench;

    protected function overrideApplicationProviders($app): array
    {
        return [
            TranslationServiceProvider::class => FixedTranslationServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $token = ParallelTesting::token();

        $this->app->useLangPath(
            $this->app->basePath('lang/' . $token)
        );
    }
}
