<?php

declare(strict_types=1);

namespace Tests\Feature\JsonFallback;

use Tests\TestCase;

use function config;

class FallbackTest extends TestCase
{
    public function testPhp(): void
    {
        $this->assertSame('German Custom Failed', __('custom.failed'));
        $this->assertSame('German Custom Password', __('custom.password'));
    }

    public function testJson(): void
    {
        $this->assertSame('German Remember Me', __('Remember Me'));
        $this->assertSame('German You are logged in!', __('You are logged in!'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.locale', 'foo');
    }
}
