<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class AvailableTest extends TestCase
{
    public function testCorrect(): void
    {
        $locales = Locales::available()->pluck('code')->all();

        $this->assertCount(count(Locale::cases()), $locales);

        $this->assertContains('fr_alias', $locales);
        $this->assertNotContains('fr', $locales);
    }

    public function testIncorrect(): void
    {
        $locales = Locales::available()->pluck('code')->all();

        $this->assertNotContains('foo', $locales);
    }
}
