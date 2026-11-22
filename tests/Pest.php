<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\TestCase;

use function Orchestra\Testbench\default_skeleton_path;
use function Pest\Laravel\withHeader;

if (isset($_SERVER['UNIQUE_TEST_TOKEN'])) {
    $cachePath = 'bootstrap/cache/mona-' . $_SERVER['UNIQUE_TEST_TOKEN'];
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

pest()
    ->printer()
    ->compact();

pest()
    ->extend(BaseTestCase::class)
    ->in('Architecture');

pest()
    ->extend(TestCase::class)
    ->in('Feature')
    ->group('feature')
    ->beforeEach(function (): void {
        Date::setTestNow('2026-11-20T22:56:24Z');

        cleanUpLang();
        fakePluginCollector();
    });

pest()
    ->in('Feature/Routes')
    ->beforeEach(function (): void {
        Event::fake();

        withHeader('Accept-Language', 'qwerty');
    });
