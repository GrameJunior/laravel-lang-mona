<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class IsProtectedTest extends TestCase
{
    public function testMain(): void
    {
        $this->assertTrue(Locales::raw()->isProtected('fr'));
        $this->assertTrue(Locales::raw()->isProtected('fr_alias'));
        $this->assertTrue(Locales::raw()->isProtected(Locale::French));

        $this->assertTrue(Locales::raw()->isProtected(Locale::German));
    }

    public function testNotProtected(): void
    {
        $this->assertFalse(Locales::raw()->isProtected(Locale::English));
        $this->assertFalse(Locales::raw()->isProtected(Locale::Swedish));
    }

    public function testIncorrect(): void
    {
        app()->setLocale('foo');
        app()->setFallbackLocale('bar');

        $this->assertFalse(Locales::raw()->isProtected('foo'));
        $this->assertFalse(Locales::raw()->isProtected('bar'));
    }
}
