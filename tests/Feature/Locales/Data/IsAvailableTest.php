<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class IsAvailableTest extends TestCase
{
    #[DataProviderExternal(TestCase::class, 'dataLocales')]
    public function testList(Locale $locale): void
    {
        $asEnum   = Locales::isAvailable($locale);
        $asString = Locales::isAvailable($locale->value);

        $this->assertTrue($asEnum);
        $this->assertTrue($asString);
    }

    public function testAlias(): void
    {
        $main  = Locales::isAvailable('fr');
        $alias = Locales::isAvailable('fr_alias');

        $this->assertTrue($main);
        $this->assertTrue($alias);
    }

    public function testEmpty(): void
    {
        $asNull   = Locales::isAvailable(null);
        $asString = Locales::isAvailable('');

        $this->assertFalse($asNull);
        $this->assertFalse($asString);
    }

    public function testIncorrect(): void
    {
        $available = Locales::isAvailable('foo');

        $this->assertFalse($available);
    }
}
