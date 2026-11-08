<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class GetCurrentTest extends TestCase
{
    public function testMain(): void
    {
        $locale = Locales::raw()->getCurrent();

        $this->assertSame('en', $locale);
    }

    public function testAliased(): void
    {
        Locales::set('fr');

        $locale = Locales::raw()->getCurrent();

        $this->assertSame('fr_alias', $locale);
        $this->assertNotSame('fr', $locale);
    }
}
