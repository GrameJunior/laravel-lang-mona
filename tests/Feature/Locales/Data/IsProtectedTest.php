<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class IsProtectedTest extends TestCase
{
    public function testMain(): void
    {
        $this->assertTrue(Locales::isProtected('fr'));
        $this->assertTrue(Locales::isProtected('fr_alias'));
        $this->assertTrue(Locales::isProtected(Locale::French));

        $this->assertTrue(Locales::isProtected(Locale::German));
    }

    public function testNotProtected(): void
    {
        $this->assertFalse(Locales::isProtected(Locale::English));
        $this->assertFalse(Locales::isProtected(Locale::Swedish));
    }

    public function testIncorrect(): void
    {
        app()->setLocale('foo');
        app()->setFallbackLocale('bar');

        $this->assertFalse(Locales::isProtected('foo'));
        $this->assertFalse(Locales::isProtected('bar'));
    }
}
