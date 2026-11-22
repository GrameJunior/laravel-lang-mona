<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Exceptions\InvalidJsonEncodeException;

require __DIR__ . '/../../vendor/autoload.php';

$fs = new Filesystem;

$native = $fs->json(__DIR__ . '/data/_native.json');

foreach ($fs->files(__DIR__ . '/data') as $file) {
    $filename = $file->getFilenameWithoutExtension();

    $content = $fs->json($file->getPathname());

    if ($filename === '_native') {
        foreach ($content as &$value) {
            $value = [
                'native'    => $value,
                'localized' => $value,
            ];
        }
    } else {
        foreach ($content as $locale => &$value) {
            $value = [
                'native'    => $native[$locale],
                'localized' => $value,
            ];
        }
    }

    try {
        $encoded = json_encode(
            $content,
            JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        );
    } catch (JsonException $exception) {
        throw InvalidJsonEncodeException::from($exception);
    }

    $fs->put($file->getPathname(), $encoded);
}
