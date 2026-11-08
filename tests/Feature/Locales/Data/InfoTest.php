<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class InfoTest extends TestCase
{
    public function testInstalled(): void
    {
        $this->assertSame('en', Locales::info(Locale::English)->code);
        $this->assertSame('de', Locales::info(Locale::German)->code);
        $this->assertSame('fr_alias', Locales::info(Locale::French)->code);
        $this->assertSame('fr_alias', Locales::info(Locale::French->value)->code);
    }

    public function testUninstalled(): void
    {
        $this->assertSame('sv', Locales::info(Locale::Swedish)->code);
        $this->assertSame('sv', Locales::info(Locale::Swedish->value)->code);
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testIncorrect(mixed $value): void
    {
        $this->assertSame('fr_alias', Locales::info($value)->code);
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testInvalidFallback(mixed $value): void
    {
        app()->setLocale($value);

        $this->assertSame('de', Locales::info('foo')->code);
    }
}
