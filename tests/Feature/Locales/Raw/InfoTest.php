<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class InfoTest extends TestCase
{
    public function testInstalled(): void
    {
        $locales = Locales::raw();

        $this->assertSame('en', $locales->info(Locale::English));
        $this->assertSame('de', $locales->info(Locale::German));
        $this->assertSame('fr_alias', $locales->info(Locale::French));
        $this->assertSame('fr_alias', $locales->info(Locale::French->value));
    }

    public function testUninstalled(): void
    {
        $locales = Locales::raw();

        $this->assertSame('sv', $locales->info(Locale::Swedish));
        $this->assertSame('sv', $locales->info(Locale::Swedish->value));
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testIncorrect(mixed $value): void
    {
        $locales = Locales::raw();

        $this->assertSame('fr_alias', $locales->info($value));
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testInvalidFallback(mixed $value): void
    {
        app()->setLocale($value);

        $locales = Locales::raw();

        $this->assertSame('de', $locales->info('foo'));
    }
}
