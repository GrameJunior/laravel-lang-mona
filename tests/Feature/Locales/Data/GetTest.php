<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class GetTest extends TestCase
{
    public function testInstalled(): void
    {
        $this->assertSame('en', Locales::get(Locale::English)->code);
        $this->assertSame('de', Locales::get(Locale::German)->code);
        $this->assertSame('fr_alias', Locales::get(Locale::French)->code);
        $this->assertSame('fr_alias', Locales::get(Locale::French->value)->code);
    }

    public function testUninstalled(): void
    {
        $this->assertSame('fr_alias', Locales::get(Locale::Swedish)->code);
        $this->assertSame('fr_alias', Locales::get(Locale::Swedish->value)->code);
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testIncorrect(mixed $value): void
    {
        $this->assertSame('fr_alias', Locales::get($value)->code);
    }

    #[DataProviderExternal(TestCase::class, 'dateIncorrectLocales')]
    public function testInvalidFallback(mixed $value): void
    {
        app()->setLocale($value);

        $this->assertSame('de', Locales::get('foo')->code);
    }
}
