<?php

declare(strict_types=1);

use LaravelLang\Exceptions\InvalidJsonEncodeException;
use LaravelLang\Exceptions\InvalidTranslationFileContentException;
use LaravelLang\Filesystem\Filesystem;

test('replaces an existing PHP translation when merging is disabled', function (): void {
    $path       = lang_path('fr/replaced.php');
    $filesystem = app(Filesystem::class);

    $filesystem->storeArray($path, ['nested' => ['old' => 'Old translation']]);
    $filesystem->storeArray($path, ['nested' => ['new' => 'New translation']]);

    expect(require $path)->toBe(['nested' => ['new' => 'New translation']]);
});

test('rejects invalid PHP translation arrays', function (string $contents, ?string $key, string $message): void {
    $path = lang_path('fr/invalid.php');
    file_put_contents($path, $contents);

    expect(fn () => app(Filesystem::class)->fromPhp($path, $key))
        ->toThrow(InvalidTranslationFileContentException::class, sprintf($message, $path));
})->with([
    'validates the file'          => ['<?php return "invalid";', null, 'The file [%s] must return an array.'],
    'validates the requested key' => [
        '<?php return ["nested" => "invalid"];',
        'nested',
        'The value for key [nested] in [%s] must be an array.',
    ],
]);

test('reports JSON encoding failures through the filesystem', function (): void {
    $path = lang_path('fr/invalid.json');

    expect(fn () => app(Filesystem::class)->storeArray($path, ['invalid' => "\xFF"]))
        ->toThrow(InvalidJsonEncodeException::class, 'Malformed UTF-8');
});
