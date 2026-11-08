<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class NotInstalledTest extends TestCase
{
    public function testMain(): void
    {
        $locales = Locales::notInstalled()->pluck('code')->all();

        $this->assertNotContains('en', $locales);
        $this->assertNotContains(app()->getLocale(), $locales);
        $this->assertNotContains(app()->getFallbackLocale(), $locales);
    }
}
