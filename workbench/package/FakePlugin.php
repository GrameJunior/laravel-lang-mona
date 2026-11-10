<?php

declare(strict_types=1);

namespace Workbench\Package;

use LaravelLang\Publisher\Plugins\Plugin;

class FakePlugin extends Plugin
{
    public function files(): array
    {
        return [
            'foo.php'  => '{locale}/auth.php',
            'bar.php'  => '{locale}/bar.php',
            'baz.json' => '{locale}.json',
        ];
    }
}
