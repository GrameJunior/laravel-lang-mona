<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use Tests\TestCase;

class IsAvailableTest extends TestCase
{
    #[DataProviderExternal(TestCase::class, 'dataLocales')]
    public function testList(Locale $locale): void
    {
        $asEnum   = Locales::raw()->isAvailable($locale);
        $asString = Locales::raw()->isAvailable($locale->value);

        $this->assertTrue($asEnum);
        $this->assertTrue($asString);
    }

    public function testAlias(): void
    {
        $main  = Locales::raw()->isAvailable('fr');
        $alias = Locales::raw()->isAvailable('fr_alias');

        $this->assertTrue($main);
        $this->assertTrue($alias);
    }

    public function testEmpty(): void
    {
        $asNull   = Locales::raw()->isAvailable(null);
        $asString = Locales::raw()->isAvailable('');

        $this->assertFalse($asNull);
        $this->assertFalse($asString);
    }

    public function testIncorrect(): void
    {
        $available = Locales::raw()->isAvailable('foo');

        $this->assertFalse($available);
    }
}
