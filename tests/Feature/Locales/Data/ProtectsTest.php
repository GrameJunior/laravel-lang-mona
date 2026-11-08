<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class ProtectsTest extends TestCase
{
    public function testMain(): void
    {
        $locales = Locales::protects()->pluck('code')->all();

        $this->assertContains('fr_alias', $locales);
        $this->assertContains('de', $locales);
        $this->assertNotContains('en', $locales);

        $this->assertCount(2, $locales);
    }

    public function testIncorrect(): void
    {
        app()->setLocale('foo');
        app()->setFallbackLocale('bar');

        $locales = Locales::protects()->pluck('code')->all();

        $this->assertContains('en', $locales);

        $this->assertCount(1, $locales);
    }
}
