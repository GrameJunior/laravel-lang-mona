<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;

it('ships every source file declared in :dataset', function (string $manifest) {
    $filesystem = new Filesystem();
    $plugins = $filesystem->json($manifest, JSON_THROW_ON_ERROR)['plugins'];
    $source = realpath(dirname($manifest) . '/source');

    foreach ($plugins as $plugin) {
        foreach ($plugin['files'] as $file) {
            $path = $source . DIRECTORY_SEPARATOR . $file['source'];

            expect($path)->toBeFile()
                ->and(realpath($path))->toStartWith($source . DIRECTORY_SEPARATOR);
        }
    }
})->with(function () {
    foreach (glob(dirname(__DIR__, 3) . '/src/*/laravel_lang.json') as $manifest) {
        yield basename(dirname($manifest)) => [$manifest];
    }
});
