<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class AvailableTest extends TestCase
{
    public function testCorrect(): void
    {
        $locales = Locales::raw()->available();

        $this->assertContains('fr_alias', $locales);
        $this->assertNotContains('fr', $locales);
    }

    public function testIncorrect(): void
    {
        $locales = Locales::raw()->available();

        $this->assertNotContains('foo', $locales);
    }
}
