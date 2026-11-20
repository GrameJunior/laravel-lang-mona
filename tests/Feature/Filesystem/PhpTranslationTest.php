<?php

declare(strict_types=1);

use LaravelLang\Filesystem\Filesystem;

test('replaces an existing PHP translation when merging is disabled', function (): void {
    $path       = lang_path('fr/replaced.php');
    $filesystem = app(Filesystem::class);

    $filesystem->storeArray($path, ['nested' => ['old' => 'Old translation']]);
    $filesystem->storeArray($path, ['nested' => ['new' => 'New translation']]);

    expect(require $path)->toBe(['nested' => ['new' => 'New translation']]);
});
