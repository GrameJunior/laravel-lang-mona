<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Toolkit\Services\TranslationFilesService;

beforeEach(function () {
    $this->translationsDirectory = sys_get_temp_dir() . '/mona-translations-' . bin2hex(random_bytes(8));
    $this->translationFilesystem = new Filesystem;
    $this->translationFilesystem->ensureDirectoryExists($this->translationsDirectory);
    $this->translationFiles = new TranslationFilesService($this->translationFilesystem);
});

afterEach(function () {
    $this->translationFilesystem->deleteDirectory($this->translationsDirectory);
});

test('JSON translations preserve nested values and sort associative keys', function () {
    $path   = $this->translationsDirectory . '/messages.json';
    $values = [
        'z' => '00123',
        'a' => [
            'z' => [true, false, null, 42, -3, 1.0, 1.25, PHP_INT_MIN, PHP_INT_MAX],
            'a' => "It's a \\path with \"quotes\", \$variables,\nnew lines,\t tabs and \0 bytes: Привет / world",
            'm' => ['001' => '002', '10.50' => '10.50', 'empty' => []],
        ],
    ];

    $this->translationFiles->store($path, $values);

    $expected = [
        'a' => [
            'a' => $values['a']['a'],
            'm' => $values['a']['m'],
            'z' => $values['a']['z'],
        ],
        'z' => '00123',
    ];

    expect($this->translationFiles->load($path))->toBe($expected);

    $content = $this->translationFilesystem->get($path);
    $this->translationFiles->store($path, $expected);

    expect($this->translationFilesystem->get($path))->toBe($content);
});

test('JSON translations accept object and array roots', function (string $content, array $expected) {
    $path = $this->translationsDirectory . '/messages.json';
    $this->translationFilesystem->put($path, $content);

    expect($this->translationFiles->load($path))->toBe($expected);
})->with([
    ['{"message":"001","nested":{"enabled":true,"missing":null}}', ['message' => '001', 'nested' => ['enabled' => true, 'missing' => null]]],
    ['["first","second"]', ['first', 'second']],
    ['{}', []],
]);

test('invalid translation files fail with their path', function (string $extension, string $content) {
    $path = $this->translationsDirectory . '/invalid.' . $extension;
    $this->translationFilesystem->put($path, $content);

    expect(fn () => $this->translationFiles->load($path))->toThrow(RuntimeException::class, $path);
})->with([
    ['json', '{"message":}'],
    ['json', 'null'],
    ['json', '"message"'],
    ['json', '42'],
    ['php', '<?php return [;'],
    ['php', '<?php return "message";'],
    ['php', '<?php /* missing return */'],
    ['php', '<?php $values = []; return $values;'],
    ['txt', '[]'],
]);

test('the JSON reader does not interpret PHP arrays', function () {
    $path = $this->translationsDirectory . '/messages.php';
    $this->translationFilesystem->put($path, '<?php return ["message" => "Hello"];');

    expect(fn () => $this->translationFiles->load($path))->toThrow(RuntimeException::class, $path);
});

test('unsupported values fail before replacing a translation file', function () {
    $path = $this->translationsDirectory . '/messages.json';
    $this->translationFilesystem->put($path, 'original');

    expect(fn () => $this->translationFiles->store($path, ['value' => new stdClass]))->toThrow(RuntimeException::class, $path)
        ->and($this->translationFilesystem->get($path))->toBe('original');
});

test('storing translations in a non-JSON file leaves it untouched', function () {
    $path    = $this->translationsDirectory . '/messages.php';
    $content = '<?php return ["message" => "Original"];';
    $this->translationFilesystem->put($path, $content);

    expect(fn () => $this->translationFiles->store($path, ['message' => 'New']))
        ->toThrow(RuntimeException::class, "Unable to write translation file [$path]: Only JSON translation files are merged.")
        ->and($this->translationFilesystem->get($path))->toBe($content);
});
