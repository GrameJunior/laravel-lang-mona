<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Config\Facades\Config;

use function Orchestra\Testbench\workbench_path;

function cleanUpHelpers(): void
{
    $filesystem = new Filesystem();

    $filesystem->deleteDirectory(
        Config::shared()->models->helpers
    );
}

function cleanUpLang(): void
{
    $filesystem = new Filesystem();

    $filesystem->deleteDirectory(lang_path());
    $filesystem->copyDirectory(workbench_path('lang'), lang_path());
}

function ensureLang(?string $locale): void
{
    if ($locale === null) {
        return;
    }

    (new Filesystem())->ensureDirectoryExists(lang_path($locale));
}
