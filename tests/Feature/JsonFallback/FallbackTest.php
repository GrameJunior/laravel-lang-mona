<?php

declare(strict_types=1);

namespace Tests\Feature\JsonFallback;

use Tests\TestCase;

use function config;

class FallbackTest extends TestCase
{
    public function testPhp(): void
    {
        $this->assertSame('French Custom Failed', __('custom.failed'));
        $this->assertSame('French Custom Password', __('custom.password'));
    }

    public function testJson(): void
    {
        $this->assertSame('French Remember Me', __('Remember Me'));
        $this->assertSame('French You are logged in!', __('You are logged in!'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.locale', 'foo');
        config()->set('app.fallback_locale', 'fr');
    }
}
