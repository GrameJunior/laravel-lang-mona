<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Request;

use function Orchestra\Testbench\default_skeleton_path;

function parallelTestingFix(): void
{
    if (! isset($_SERVER['UNIQUE_TEST_TOKEN'])) {
        return;
    }

    $cachePath = 'bootstrap/cache/mona-' . Request::server('UNIQUE_TEST_TOKEN');
    $directory = default_skeleton_path() . '/' . $cachePath;
    $files     = new Filesystem;

    $files->ensureDirectoryExists($directory);

    // Laravel reads the manifest paths before registering the Workbench provider.
    $_SERVER['APP_PACKAGES_CACHE'] = $cachePath . '/packages.php';
    $_SERVER['APP_SERVICES_CACHE'] = $cachePath . '/services.php';

    register_shutdown_function(static function () use ($files, $directory): void {
        $files->deleteDirectory($directory);
    });
}
