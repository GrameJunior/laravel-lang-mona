<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Factory;
use LaravelLang\Toolkit\Commands\DownloadCommand;
use LaravelLang\Toolkit\Services\TranslationFiles;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

beforeEach(function () {
    $this->downloadFilesystem = new Filesystem();
    $this->downloadRoot = sys_get_temp_dir() . '/mona-download-' . bin2hex(random_bytes(8));
    $this->downloadFilesystem->ensureDirectoryExists($this->downloadRoot);
    $this->downloadWorkspaces = [];
    $this->downloadHttp = new Factory();
    $this->downloadHttp->preventStrayRequests();
});

afterEach(function () {
    foreach ($this->downloadWorkspaces as $workspace) {
        if (str_starts_with(str_replace('\\', '/', $workspace), str_replace('\\', '/', sys_get_temp_dir()) . '/laravel-lang-toolkit-')) {
            $this->downloadFilesystem->deleteDirectory($workspace);
        }
    }

    $this->downloadFilesystem->deleteDirectory($this->downloadRoot);
});

function downloadZip(string $directory, array $files, ?Closure $configure = null): string
{
    $path = $directory . '/fixture.zip';
    $zip = new ZipArchive();

    expect($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE))->toBeTrue();

    foreach ($files as $name => $content) {
        $zip->addFromString($name, $content);
    }

    $configure?->__invoke($zip);
    $zip->close();

    return (new Filesystem())->get($path);
}

function downloadTester(Filesystem $filesystem, Factory $http): CommandTester
{
    $application = new Application();
    $application->addCommand(new DownloadCommand($filesystem, $http));

    return new CommandTester($application->find('download'));
}

function downloadOptions(string $root, array $options = []): array
{
    return array_replace([
        '--url' => 'https://example.test/repository.zip?download=1',
        '--directory' => $root . '/source/framework/13.x',
    ], $options);
}

function downloadFake(Factory $http, array &$workspaces, string $body, int $status = 200): void
{
    $http->fake(function ($request, array $options) use (&$workspaces, $body, $status) {
        $workspaces[] = dirname($options['sink']);

        return Factory::response($body, $status);
    });
}

it('exposes only the replacement download options', function () {
    $application = new Application();
    $application->addCommand(new DownloadCommand($this->downloadFilesystem, $this->downloadHttp));
    $definition = $application->find('download')->getDefinition();

    foreach (['url', 'directory', 'paths', 'only-copy'] as $option) {
        expect($definition->hasOption($option))->toBeTrue();
    }

    expect($definition->hasOption('path'))->toBeFalse()
        ->and($definition->hasOption('ver'))->toBeFalse()
        ->and($definition->getOption('paths')->isArray())->toBeTrue();
});

it('downloads discovers and merges JSON into the exact destination', function () {
    $filesystem = new class extends Filesystem
    {
        public function moveDirectory($from, $to, $overwrite = false)
        {
            throw new RuntimeException('Directory moves can fail across volumes.');
        }
    };
    $translations = new TranslationFiles();
    $target = $this->downloadRoot . '/source/laravel/framework/13.x';
    $this->downloadFilesystem->ensureDirectoryExists($target);
    $translations->store($target . '/en.json', ['kept' => 'Local value', 'failed' => 'Old value']);
    $this->downloadFilesystem->put($target . '/auth.php', '<?php return ["local" => "Must be replaced"];');
    $this->downloadFilesystem->put($target . '/manual.txt', 'Keep this file');
    $lastPhp = '<?php /* Keep the last PHP file unchanged. */ return ["nested" => ["two" => "Second"]];';

    $body = downloadZip($this->downloadRoot, [
        'unexpected-archive-root/app.php' => '<?php __("A new message"); __("Another message");',
        'unexpected-archive-root/lang/en/auth.php' => '<?php return ["failed" => "New value", "nested" => ["one" => "First"]];',
        'unexpected-archive-root/lang/vendor/auth.php' => $lastPhp,
        'unexpected-archive-root/lang/en/en.json' => '{"failed": "New value", "nested": {"one": "First"}}',
        'unexpected-archive-root/lang/vendor/en.json' => '{"nested": {"two": "Second"}}',
        'unexpected-archive-root/lang/en/extra.json' => '{"Example": "Example"}',
    ]);
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, $body);
    $tester = downloadTester($filesystem, $this->downloadHttp);

    $status = $tester->execute(downloadOptions($this->downloadRoot, [
        '--directory' => $target,
        '--paths' => ['lang'],
    ]));

    expect($status)->toBe(Command::SUCCESS)
        ->and($translations->load($target . '/framework.json'))->toBe([
            'A new message' => 'A new message',
            'Another message' => 'Another message',
        ])
        ->and($translations->load($target . '/en.json'))->toBe([
            'failed' => 'New value',
            'kept' => 'Local value',
            'nested' => ['one' => 'First', 'two' => 'Second'],
        ])
        ->and($this->downloadFilesystem->get($target . '/auth.php'))->toBe($lastPhp)
        ->and($translations->load($target . '/extra.json'))->toBe(['Example' => 'Example'])
        ->and($this->downloadFilesystem->get($target . '/manual.txt'))->toBe('Keep this file')
        ->and($this->downloadFilesystem->glob($this->downloadRoot . '/source/.download-*'))->toBe([]);
    expect($this->downloadWorkspaces)->toHaveCount(1)
        ->and(str_replace('\\', '/', dirname($this->downloadWorkspaces[0])))->toBe(str_replace('\\', '/', sys_get_temp_dir()))
        ->and(basename($this->downloadWorkspaces[0]))->toStartWith('laravel-lang-toolkit-')
        ->and(is_dir($this->downloadWorkspaces[0]))->toBeFalse();
    $this->downloadHttp->assertSentCount(1);
});

it('copies several paths without changing discovered keys in only-copy mode', function () {
    $target = $this->downloadRoot . '/source/framework/13.x';
    $this->downloadFilesystem->ensureDirectoryExists($target);
    $this->downloadFilesystem->put($target . '/framework.json', '{"Original": "Original"}');
    $body = downloadZip($this->downloadRoot, [
        'repo/app.php' => '<?php __("Do not discover this");',
        'repo/first/en.json' => '{"First": "First", "Shared": "Old"}',
        'repo/second/en.json' => '{"Second": "Second", "Shared": "New"}',
        'repo/first/en.php' => '<?php return ["first" => "First"];',
        'repo/second/en.php' => '<?php /* Last copy wins. */ return ["second" => "Second"];',
    ]);
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, $body);
    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot, [
        '--paths' => ['first', 'second'],
        '--only-copy' => true,
    ])))->toBe(Command::SUCCESS)
        ->and((new TranslationFiles())->load($target . '/en.json'))->toBe([
            'First' => 'First', 'Second' => 'Second', 'Shared' => 'New',
        ])
        ->and($this->downloadFilesystem->get($target . '/en.php'))->toBe('<?php /* Last copy wins. */ return ["second" => "Second"];')
        ->and($this->downloadFilesystem->get($target . '/framework.json'))->toBe('{"Original": "Original"}');
});

it('copies PHP bytes without parsing or executing downloaded code', function () {
    $target = $this->downloadRoot . '/source/framework/13.x';
    $executed = $this->downloadRoot . '/executed.txt';
    $php = "<?php\n\n// Keep this formatting and comment.\nfile_put_contents(" . var_export($executed, true) . ", 'BAD');\n" . <<<'PHP'
$label = "Don't rewrite \"quotes\"";

return [
    'multiline' => "First
Second",
    'dynamic' => getenv('TRANSLATION_VALUE'),
];

PHP;
    $invalid = '<?php return [';
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, downloadZip($this->downloadRoot, [
        'repo/lang/en.php' => $php,
        'repo/lang/unfinished.php' => $invalid,
    ]));
    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot, [
        '--paths' => ['lang'],
        '--only-copy' => true,
    ])))->toBe(Command::SUCCESS)
        ->and($this->downloadFilesystem->get($target . '/en.php'))->toBe($php)
        ->and($this->downloadFilesystem->get($target . '/unfinished.php'))->toBe($invalid)
        ->and(is_file($executed))->toBeFalse();
});

it('discovers keys without copy paths and does not create empty discovery files', function (string $content, bool $expected) {
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, downloadZip($this->downloadRoot, [
        'repo/app.php' => $content,
    ]));

    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot)))->toBe(Command::SUCCESS)
        ->and(is_file($this->downloadRoot . '/source/framework/13.x/framework.json'))->toBe($expected);
})->with([
    'with keys' => ['<?php __("A message");', true],
    'without keys' => ['<?php $value = 1;', false],
]);

it('resolves a relative final destination from the current directory', function () {
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, downloadZip($this->downloadRoot, [
        'repo/app.php' => '<?php __("A message");',
    ]));
    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);
    $previous = getcwd();
    $options = downloadOptions($this->downloadRoot, ['--directory' => 'chosen/framework/13.x']);
    chdir($this->downloadRoot);

    try {
        $status = $tester->execute($options);
    } finally {
        chdir($previous);
    }

    expect($status)->toBe(Command::SUCCESS)
        ->and(is_file($this->downloadRoot . '/chosen/framework/13.x/framework.json'))->toBeTrue()
        ->and(is_dir($this->downloadRoot . '/source'))->toBeFalse();
});

it('refuses to replace a source path that is already a file', function () {
    $this->downloadFilesystem->put($this->downloadRoot . '/source', 'Existing file');
    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot)))->toBe(Command::FAILURE)
        ->and($this->downloadFilesystem->get($this->downloadRoot . '/source'))->toBe('Existing file');
    $this->downloadHttp->assertNothingSent();
});

it('rejects missing or unsafe options before downloading', function (array $options) {
    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot, $options)))->toBe(Command::FAILURE);
    $this->downloadHttp->assertNothingSent();
})->with([
    'missing URL' => [['--url' => '']],
    'missing directory' => [['--directory' => '']],
    'file URL' => [['--url' => 'file:///etc/passwd']],
    'destination traversal' => [['--directory' => '../outside']],
    'Windows drive-relative path' => [['--directory' => 'C:outside']],
    'copy traversal' => [['--paths' => ['../outside']]],
    'absolute copy path' => [['--paths' => ['/outside']]],
]);

it('preserves prior files and cleans temporary data on download or processing failures', function (string $failure) {
    $target = $this->downloadRoot . '/source/framework/13.x';
    $this->downloadFilesystem->ensureDirectoryExists($target);
    $this->downloadFilesystem->put($target . '/en.json', '{"Original": "Original"}');
    $this->downloadFilesystem->ensureDirectoryExists($this->downloadRoot . '/tmp/unrelated');
    $this->downloadFilesystem->put($this->downloadRoot . '/tmp/unrelated/keep.txt', 'Keep');

    $body = match ($failure) {
        'invalid ZIP', 'HTTP failure' => 'not a ZIP',
        'traversal' => downloadZip($this->downloadRoot, ['../escape.txt' => 'Escape']),
        'backslash entry' => downloadZip($this->downloadRoot, ['repo\\escape.php' => '<?php return [];']),
        'symlink' => downloadZip($this->downloadRoot, ['repo/link' => '/outside'], function (ZipArchive $zip) {
            $zip->setExternalAttributesName('repo/link', ZipArchive::OPSYS_UNIX, 0120777 << 16);
        }),
        'invalid JSON' => downloadZip($this->downloadRoot, ['repo/lang/en.json' => '{broken']),
        'missing path' => downloadZip($this->downloadRoot, ['repo/app.php' => '<?php __("A message");']),
    };
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, $body, $failure === 'HTTP failure' ? 404 : 200);
    $tester = downloadTester($this->downloadFilesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot, ['--paths' => ['lang']])))->toBe(Command::FAILURE)
        ->and($this->downloadWorkspaces)->toHaveCount(1)
        ->and($this->downloadFilesystem->get($target . '/en.json'))->toBe('{"Original": "Original"}')
        ->and($this->downloadFilesystem->glob($target . '/*'))->toHaveCount(1)
        ->and($this->downloadFilesystem->glob($this->downloadRoot . '/source/.download-*'))->toBe([])
        ->and($this->downloadFilesystem->get($this->downloadRoot . '/tmp/unrelated/keep.txt'))->toBe('Keep');
    expect(is_dir($this->downloadWorkspaces[0]))->toBeFalse();
})->with(['HTTP failure', 'invalid ZIP', 'traversal', 'backslash entry', 'symlink', 'invalid JSON', 'missing path']);

it('restores previous files and removes new files after a partial publish fails', function () {
    $filesystem = new class extends Filesystem
    {
        public array $deletedDirectories = [];

        public function copyDirectory($from, $to, $options = null)
        {
            $copied = parent::copyDirectory($from, $to, $options);

            return str_ends_with(str_replace('\\', '/', $from), '/result') ? false : $copied;
        }

        public function deleteDirectory($directory, $preserve = false)
        {
            $this->deletedDirectories[] = str_replace('\\', '/', $directory);

            return parent::deleteDirectory($directory, $preserve);
        }
    };
    $target = $this->downloadRoot . '/source/framework/13.x';
    $filesystem->ensureDirectoryExists($target);
    $filesystem->put($target . '/original.txt', 'Original');
    $filesystem->put($target . '/en.json', '{"Value": "Original"}');
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, downloadZip($this->downloadRoot, [
        'repo/app.php' => '<?php __("A message");',
        'repo/lang/en.json' => '{"Value": "Changed"}',
        'repo/lang/new.json' => '{"New": "New"}',
    ]));
    $tester = downloadTester($filesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot, ['--paths' => ['lang']])))->toBe(Command::FAILURE)
        ->and($this->downloadWorkspaces)->toHaveCount(1)
        ->and($filesystem->get($target . '/original.txt'))->toBe('Original')
        ->and($filesystem->get($target . '/en.json'))->toBe('{"Value": "Original"}')
        ->and(is_file($target . '/new.json'))->toBeFalse()
        ->and(is_file($target . '/framework.json'))->toBeFalse()
        ->and($filesystem->deletedDirectories)->not->toContain(str_replace('\\', '/', $target))
        ->and(is_dir($this->downloadWorkspaces[0]))->toBeFalse()
        ->and($filesystem->glob($this->downloadRoot . '/source/.download-*'))->toBe([]);
});

it('retains the recovery snapshot if the filesystem also refuses rollback', function () {
    $filesystem = new class extends Filesystem
    {
        public function copyDirectory($from, $to, $options = null)
        {
            $from = str_replace('\\', '/', $from);

            return str_ends_with($from, '/result') || str_ends_with($from, '/previous')
                ? false
                : parent::copyDirectory($from, $to, $options);
        }
    };
    $target = $this->downloadRoot . '/source/framework/13.x';
    $filesystem->ensureDirectoryExists($target);
    $filesystem->put($target . '/original.txt', 'Original');
    downloadFake($this->downloadHttp, $this->downloadWorkspaces, downloadZip($this->downloadRoot, [
        'repo/app.php' => '<?php __("A message");',
    ]));
    $tester = downloadTester($filesystem, $this->downloadHttp);

    expect($tester->execute(downloadOptions($this->downloadRoot)))->toBe(Command::FAILURE)
        ->and($this->downloadWorkspaces)->toHaveCount(1);
    $snapshots = $filesystem->glob($this->downloadWorkspaces[0] . '/previous/original.txt');

    expect($snapshots)->toHaveCount(1)
        ->and($filesystem->get($snapshots[0]))->toBe('Original')
        ->and($filesystem->get($target . '/original.txt'))->toBe('Original')
        ->and($tester->getDisplay())->toContain('previous files are preserved');
});
