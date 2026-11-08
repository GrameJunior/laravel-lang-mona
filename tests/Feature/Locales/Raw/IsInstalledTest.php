<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Raw;

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class IsInstalledTest extends TestCase
{
    public function testMain(): void
    {
        $this->assertTrue(Locales::raw()->isInstalled('en'));
        $this->assertTrue(Locales::raw()->isInstalled('de'));
        $this->assertTrue(Locales::raw()->isInstalled('fr'));
        $this->assertTrue(Locales::raw()->isInstalled('fr_alias'));
    }

    public function testEmpty(): void
    {
        $asNull   = Locales::raw()->isInstalled(null);
        $asString = Locales::raw()->isInstalled('');

        $this->assertFalse($asNull);
        $this->assertFalse($asString);
    }

    public function testIncorrect(): void
    {
        (new Filesystem())->ensureDirectoryExists(lang_path('bar'));

        $foo = Locales::raw()->isInstalled('foo');
        $bar = Locales::raw()->isInstalled('bar');

        $this->assertFalse($foo);
        $this->assertFalse($bar);
    }
}
