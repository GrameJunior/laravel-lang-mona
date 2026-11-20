<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use LaravelLang\LocaleList\Locale;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;

beforeEach(function () {
    $this->toolkitFilesystem = new Filesystem();
    $this->toolkitDirectory = sys_get_temp_dir() . '/mona-toolkit-' . bin2hex(random_bytes(8));
    $this->toolkitFilesystem->ensureDirectoryExists($this->toolkitDirectory);
});

afterEach(function () {
    $this->toolkitFilesystem->deleteDirectory($this->toolkitDirectory);
});

function toolkitFile(string $path, string $contents): void
{
    $filesystem = new Filesystem();
    $filesystem->ensureDirectoryExists(dirname($path));
    $filesystem->put($path, $contents);
}

function runToolkit(string ...$arguments): Process
{
    $process = new Process([PHP_BINARY, dirname(__DIR__, 3) . '/src/Toolkit/bin/toolkit', ...$arguments]);
    $process->run();

    return $process;
}

it('prunes obsolete translations and preserves active translations and metadata', function (string $command) {
    $root = $this->toolkitDirectory;
    $package = $root . '/Package';
    $locales = ($command === 'sync:package' ? $package : $root . '/Library') . '/locales';
    $directory = $command === 'sync:package' ? $package : $root;

    toolkitFile($package . '/laravel_lang.json', '{}');
    toolkitFile($package . '/source/messages.json', '{"Hello":"Hello","0":"0"}');
    toolkitFile($package . '/source/subdirectory/labels.php', '<?php return ["nested" => ["first" => "World"], "attributes" => []];');
    toolkitFile($package . '/source/_settings.json', '{"Obsolete":"Obsolete"}');
    toolkitFile($locales . '/fr/messages.json', '{"Hello":"Bonjour","World":"Monde","Obsolete":"Périmé","0":"Zéro"}');
    toolkitFile($locales . '/fr/numeric.json', '{"0":"Zéro","Obsolete":"Périmé"}');
    toolkitFile($locales . '/fr/_settings.json', '{"excludes":["Hello"]}');
    toolkitFile($locales . '/fr/_status.json', '{"messages":{"Hello":"Hello"}}');

    $process = runToolkit($command, $directory);

    expect($process->getExitCode())->toBe(Command::SUCCESS, $process->getErrorOutput())
        ->and($process->getErrorOutput())->toBe('')
        ->and($this->toolkitFilesystem->json($locales . '/fr/messages.json'))->toBe([
            'Hello' => 'Bonjour', 'World' => 'Monde', 0 => 'Zéro',
        ])
        ->and(json_decode($this->toolkitFilesystem->get($locales . '/fr/numeric.json'), flags: JSON_THROW_ON_ERROR))
        ->toEqual((object) ['0' => 'Zéro'])
        ->and($this->toolkitFilesystem->get($locales . '/fr/_settings.json'))->toBe('{"excludes":["Hello"]}')
        ->and($this->toolkitFilesystem->get($locales . '/fr/_status.json'))->toBe('{"messages":{"Hello":"Hello"}}');
})->with(['sync:package', 'sync:library']);

it('keeps synchronized PHP translations readable on repeated runs', function () {
    $root = $this->toolkitDirectory;
    $path = $root . '/locales/fr/messages.php';
    toolkitFile($root . '/source/messages.php', '<?php return ["greeting" => "Hello"];');
    toolkitFile($path, '<?php return ["Hello" => "Bonjour", "Obsolete" => "Périmé"];');

    for ($run = 0; $run < 2; $run++) {
        expect(runToolkit('sync:package', $root)->getExitCode())->toBe(Command::SUCCESS)
            ->and($this->toolkitFilesystem->get($path))->toStartWith('<?php');
        expect(require $path)->toBe(['Hello' => 'Bonjour']);
    }
});

it('preserves translation files when synchronization sources are incomplete', function (string $command, ?string $source, string $extension = 'json') {
    $root = $this->toolkitDirectory;
    $package = $root . '/Package';
    $locales = ($command === 'sync:package' ? $package : $root . '/Library') . '/locales';
    $directory = $command === 'sync:package' ? $package : $root;
    $translation = '{"Hello":"Bonjour","World":"Monde"}';

    toolkitFile($package . '/laravel_lang.json', '{}');
    toolkitFile($locales . '/fr/messages.json', $translation);
    $this->toolkitFilesystem->ensureDirectoryExists($package . '/source');

    if ($source !== null) {
        toolkitFile($package . '/source/first.json', '{"Hello":"Hello"}');
        toolkitFile($package . '/source/messages.' . $extension, $source);
    }

    expect(runToolkit($command, $directory)->getExitCode())->toBe(Command::FAILURE)
        ->and($this->toolkitFilesystem->get($locales . '/fr/messages.json'))->toBe($translation);
})->with(['sync:package', 'sync:library'])->with([
    'no source files' => [null],
    'empty source file' => ['{}'],
    'invalid JSON' => ['{broken'],
    'non-object JSON' => ['"World"'],
    'invalid PHP' => ['<?php return [', 'php'],
    'non-array PHP' => ['<?php return "World";', 'php'],
    'invalid PHP translation value' => ['<?php return ["label" => false];', 'php'],
]);

it('uses locale settings when reporting missing translations and removes an obsolete status file', function () {
    $root = $this->toolkitDirectory;
    $locale = $root . '/locales/fr';
    toolkitFile($root . '/locales/_settings.json', '{"excludes":["Global"]}');
    toolkitFile($locale . '/_settings.json', '{"excludes":["Local"]}');
    toolkitFile($locale . '/messages.json', '{"Zoo":"Zoo","ant":"ant","Local":"Local","Global":"Global","Hello":"Bonjour"}');
    toolkitFile($locale . '/other.json', '{"World":"Monde"}');
    toolkitFile($locale . '/_status.json', '{"outdated":{}}');

    $process = runToolkit('status', $root);

    expect($process->getExitCode())->toBe(Command::SUCCESS, $process->getErrorOutput())
        ->and($this->toolkitFilesystem->json($locale . '/_status.json'))->toBe([
            'messages' => ['ant' => 'ant', 'Zoo' => 'Zoo'],
        ]);

    toolkitFile($locale . '/messages.json', '{"Zoo":"Parc","ant":"Fourmi","Local":"Local","Global":"Global"}');

    expect(runToolkit('status', $root)->getExitCode())->toBe(Command::SUCCESS)
        ->and($this->toolkitFilesystem->exists($locale . '/_status.json'))->toBeFalse()
        ->and($this->toolkitFilesystem->get($locale . '/_settings.json'))->toBe('{"excludes":["Local"]}');
});

it('creates and removes only the intended locale directories on repeated runs', function () {
    $root = $this->toolkitDirectory . '/target';
    $locales = $root . '/locales';
    $neighbor = $this->toolkitDirectory . '/neighbor/locales';
    toolkitFile($root . '/source/messages.json', '{"Hello":"Hello"}');
    toolkitFile($locales . '/_settings.json', '{"excludes":[]}');
    toolkitFile($locales . '/fr/messages.json', '{"Hello":"Bonjour"}');
    toolkitFile($locales . '/retired/messages.json', '{"Hello":"Old"}');
    toolkitFile($neighbor . '/retired/messages.json', '{"Hello":"Neighbor"}');

    for ($run = 0; $run < 2; $run++) {
        expect(runToolkit('create-locale', $root)->getExitCode())->toBe(Command::SUCCESS)
            ->and(array_map(basename(...), $this->toolkitFilesystem->directories($locales)))
            ->toEqualCanonicalizing([...Locale::values(), 'retired']);
    }

    for ($run = 0; $run < 2; $run++) {
        expect(runToolkit('delete-locale', $root)->getExitCode())->toBe(Command::SUCCESS)
            ->and(array_map(basename(...), $this->toolkitFilesystem->directories($locales)))
            ->toEqualCanonicalizing(Locale::values());
    }

    expect($this->toolkitFilesystem->get($root . '/source/messages.json'))->toBe('{"Hello":"Hello"}')
        ->and($this->toolkitFilesystem->get($locales . '/_settings.json'))->toBe('{"excludes":[]}')
        ->and($this->toolkitFilesystem->get($locales . '/fr/messages.json'))->toBe('{"Hello":"Bonjour"}')
        ->and($this->toolkitFilesystem->get($neighbor . '/retired/messages.json'))->toBe('{"Hello":"Neighbor"}');
});
