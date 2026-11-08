<?php

declare(strict_types=1);

namespace Tests\Feature\JsonFallback;

use Tests\TestCase;

class DefaultTest extends TestCase
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
}
