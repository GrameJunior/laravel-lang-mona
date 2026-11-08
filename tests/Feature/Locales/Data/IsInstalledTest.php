<?php

declare(strict_types=1);

namespace Tests\Feature\Locales\Data;

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Locales\Facades\Locales;
use Tests\TestCase;

class IsInstalledTest extends TestCase
{
    public function testMain(): void
    {
        $this->assertTrue(Locales::isInstalled('en'));
        $this->assertTrue(Locales::isInstalled('de'));
        $this->assertTrue(Locales::isInstalled('fr'));
        $this->assertTrue(Locales::isInstalled('fr_alias'));
    }

    public function testEmpty(): void
    {
        $asNull   = Locales::isInstalled(null);
        $asString = Locales::isInstalled('');

        $this->assertFalse($asNull);
        $this->assertFalse($asString);
    }

    public function testIncorrect(): void
    {
        (new Filesystem())->ensureDirectoryExists(lang_path('bar'));

        $foo = Locales::isInstalled('foo');
        $bar = Locales::isInstalled('bar');

        $this->assertFalse($foo);
        $this->assertFalse($bar);
    }
}
