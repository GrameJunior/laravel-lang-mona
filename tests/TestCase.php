<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTests;

class TestCase extends BaseTests
{
    use WithWorkbench;
}
