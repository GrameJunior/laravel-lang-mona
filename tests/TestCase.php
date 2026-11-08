<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Translation\TranslationServiceProvider;
use LaravelLang\JsonFallback\TranslationServiceProvider as FixedTranslationServiceProvider;
use LaravelLang\LocaleList\Locale;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTests;

class TestCase extends BaseTests
{
    use WithWorkbench;

    public static function dataLocales(): array
    {
        return array_map(static fn (Locale $locale): array => [$locale], Locale::cases());
    }

    protected function overrideApplicationProviders($app): array
    {
        return [
            TranslationServiceProvider::class => FixedTranslationServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setLangPath();
        $this->cleanUpLang();
    }

    protected function setLangPath(): void
    {
        $token = ParallelTesting::token();

        $this->app->useLangPath(
            $this->app->basePath('lang/' . $token)
        );
    }

    protected function cleanUpLang(): void
    {
        $filesystem = new Filesystem();

        $filesystem->deleteDirectory(lang_path());
        $filesystem->copyDirectory(__DIR__ . '/../workbench/lang', lang_path());
    }
}
