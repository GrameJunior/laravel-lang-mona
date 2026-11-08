<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class GetTest extends TestCase
{
    public function testInstalled(): void
    {
        $locales = Locales::raw();

        $this->assertSame('en', $locales->get(Locale::English));
        $this->assertSame('de', $locales->get(Locale::German));
        $this->assertSame('fr_alias', $locales->get(Locale::French));
        $this->assertSame('fr_alias', $locales->get(Locale::French->value));
    }

    public function testUninstalled(): void
    {
        $locales = Locales::raw();

        $this->assertSame('fr_alias', $locales->get(Locale::Swedish));
        $this->assertSame('fr_alias', $locales->get(Locale::Swedish->value));
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testIncorrect(mixed $value): void
    {
        $locales = Locales::raw();

        $this->assertSame('fr_alias', $locales->get($value));
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testInvalidFallback(mixed $value): void
    {
        app()->setLocale($value);

        $locales = Locales::raw();

        $this->assertSame('de', $locales->get('foo'));
    }
}
