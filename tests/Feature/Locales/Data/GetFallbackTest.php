<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class GetFallbackTest extends TestCase
{
    public function testMain(): void
    {
        app()->setFallbackLocale('de');

        $locale = Locales::getFallback()->code;

        $this->assertSame('de', $locale);
    }

    public function testAlias(): void
    {
        app()->setFallbackLocale('fr');

        $locale = Locales::getFallback()->code;

        $this->assertSame('fr_alias', $locale);
    }
}
