<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class InstalledTest extends TestCase
{
    public function testCorrect(): void
    {
        $locales = Locales::raw()->installed();

        $this->assertContains('en', $locales);
        $this->assertContains('de', $locales);
        $this->assertContains('fr_alias', $locales);

        $this->assertCount(3, $locales);
    }

    public function testIncorrect(): void
    {
        (new Filesystem())->ensureDirectoryExists(lang_path('foo'));

        $locales = Locales::raw()->installed();

        $this->assertContains('en', $locales);
        $this->assertContains('de', $locales);
        $this->assertContains('fr_alias', $locales);

        $this->assertNotContains('foo', $locales);

        $this->assertCount(3, $locales);
    }
}
