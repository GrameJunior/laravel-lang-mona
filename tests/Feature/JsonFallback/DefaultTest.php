<?php

declare(strict_types=1);

namespace Tests\Feature\JsonFallback;

use Illuminate\Filesystem\Filesystem;
use Tests\TestCase;

use function lang_path;

class DefaultTest extends TestCase
{
    public function testPhp(): void
    {
        $this->assertSame('English Custom Failed', __('custom.failed'));
        $this->assertSame('English Custom Password', __('custom.password'));
    }

    public function testJson(): void
    {
        $this->assertSame('English Remember Me', __('Remember Me'));
        $this->assertSame('English You are logged in!', __('You are logged in!'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        (new Filesystem())->deleteDirectory(lang_path());
        (new Filesystem())->copyDirectory(__DIR__ . '/../../../workbench/lang', lang_path());
    }
}
