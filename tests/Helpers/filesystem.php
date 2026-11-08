<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;

use function Orchestra\Testbench\workbench_path;

function cleanUpLang(): void
{
    $filesystem = new Filesystem();

    $filesystem->deleteDirectory(lang_path());
    $filesystem->copyDirectory(workbench_path('lang'), lang_path());
}

function ensureLang(string $locale): void
{
    (new Filesystem())->ensureDirectoryExists(lang_path($locale));
}
