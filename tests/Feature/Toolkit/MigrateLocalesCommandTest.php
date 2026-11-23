<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use LaravelLang\Toolkit\Commands\MigrateLocalesCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

it('migrates one legacy project with an explicit or prompted target', function (bool $interactive): void {
    $filesystem = new Filesystem;
    $root       = sys_get_temp_dir() . '/mona-migrate-' . bin2hex(random_bytes(8));
    $project    = $root . '/Legacy project';
    $target     = $root . '/New locales';
    $legacy     = $project . '/locales/fr';
    $locale     = $target . '/fr';

    try {
        $fixtures = [
            $project . '/source/messages.json'             => '{"greeting":"Hello","0":"0","invalid":"Invalid :ATTRIBUTE","empty":"","null":null}',
            $project . '/source/framework/12/messages.php' => '<?php return ["nested" => ["name" => "Name", "invalid" => "Missing :attribute", "plain" => "Plain"]];',
            $project . '/source/_settings.json'            => '{"internal":"Internal label"}',
            $project . '/source/notes.txt'                 => 'Source documentation',
            $legacy . '/json.json'                         => '{"greeting":"Bonjour","0":"0","obsolete":"Ancien","empty":"Empty","null":"Null","internal":"Internal"}',
            $legacy . '/php.json'                          => '{"nested.name":"Nom"}',
            $legacy . '/json-inline.json'                  => '{"invalid":"Attribut invalide","greeting":"Salut"}',
            $legacy . '/php-inline.json'                   => '{"nested.invalid":"Attribut manquant","nested.plain":"Simple"}',
            $legacy . '/_excludes.json'                    => '["Name","Hello","Name"]',
            $locale . '/default.json'                      => '{"Hello":"Salut","Another greeting":"Bonjour"}',
            $locale . '/_settings.json'                    => '{"excludes":["Hello","Already excluded"],"custom":true}',
        ];

        foreach ($fixtures as $path => $content) {
            $filesystem->ensureDirectoryExists(dirname($path));
            $filesystem->put($path, $content);
        }

        $tester = $interactive
            ? runLocaleMigration($project, [], [$target])
            : runLocaleMigration($project, ['--target' => $target, '--no-interaction' => true]);

        expect($tester->getStatusCode())->toBe(Command::SUCCESS, $tester->getErrorOutput())
            ->and($tester->getDisplay())->toContain('Locale files migrated.')
            ->and($filesystem->json($locale . '/default.json'))->toEqual([
                0                  => '0',
                'Another greeting' => 'Bonjour',
                'Hello'            => 'Bonjour',
                'Name'             => 'Nom',
            ])
            ->and($filesystem->json($locale . '/inline.json'))->toEqual([
                'Invalid :ATTRIBUTE' => 'Attribut invalide',
                'Missing :attribute' => 'Attribut manquant',
            ])
            ->and($filesystem->json($locale . '/_settings.json'))->toEqual([
                'custom'   => true,
                'excludes' => ['Already excluded', 'Hello', 'Name'],
            ])
            ->and($filesystem->get($legacy . '/json.json'))->toBe($fixtures[$legacy . '/json.json']);
    } finally {
        $filesystem->deleteDirectory($root);
    }
})->with(['explicit target' => [false], 'interactive target' => [true]]);

it('leaves the target unchanged when migration input is invalid', function (array $files, string $diagnostic): void {
    $filesystem = new Filesystem;
    $root       = sys_get_temp_dir() . '/mona-migrate-' . bin2hex(random_bytes(8));
    $project    = $root . '/project';
    $target     = $root . '/target';
    $existing   = $target . '/de/default.json';

    try {
        $fixtures = $files + [
            'project/source/messages.json' => '{"Hello":"Hello"}',
            'project/locales/de/json.json' => '{"Hello":"Hallo"}',
            'target/de/default.json'       => '{"Hello":"Guten Tag"}',
        ];

        foreach ($fixtures as $path => $content) {
            $path = $root . '/' . $path;
            $filesystem->ensureDirectoryExists(dirname($path));
            $filesystem->put($path, $content);
        }

        $tester = runLocaleMigration($project, ['--target' => $target, '--no-interaction' => true]);

        expect($tester->getStatusCode())->toBe(Command::FAILURE)
            ->and(preg_replace('/\s+/', ' ', $tester->getErrorOutput()))->toContain($diagnostic)
            ->and($filesystem->get($existing))->toBe($fixtures['target/de/default.json'])
            ->and($filesystem->exists($target . '/fr'))->toBeFalse();
    } finally {
        $filesystem->deleteDirectory($root);
    }
})->with([
    'malformed locale JSON'    => [['project/locales/fr/json.json' => '{broken'], 'Unable to read translation file'],
    'empty translation source' => [['project/source/messages.json' => '{}'], 'No translation sources found'],
    'malformed source JSON'    => [['project/source/messages.json' => '{broken'], 'Unable to read translation source'],
    'non-string source phrase' => [['project/source/messages.json' => '{"Hello":true}'], 'neither a string nor an integer'],
    'nested translation'       => [['project/locales/fr/json.json' => '{"Hello":["Bonjour"]}'], 'Expected a scalar translation for [Hello]'],
    'exclusion object'         => [['project/locales/fr/_excludes.json' => '{"Hello":"Bonjour"}'], 'Expected a list of exclusions'],
    'numeric exclusion'        => [['project/locales/fr/_excludes.json' => '[1]'], 'Expected string exclusions'],
    'scalar target dictionary' => [['target/de/default.json' => '42'], 'Unable to read translation file'],
    'non-finite translation'   => [['project/locales/fr/json.json' => '{"Hello":1e999}'], 'Unable to write translation file'],
]);

it('reports missing migration inputs before creating the target', function (array $directories, bool $provideTarget, string $diagnostic): void {
    $filesystem = new Filesystem;
    $root       = sys_get_temp_dir() . '/mona-migrate-' . bin2hex(random_bytes(8));
    $project    = $root . '/project';
    $target     = $root . '/target';

    try {
        foreach ($directories as $directory) {
            $filesystem->ensureDirectoryExists($project . '/' . $directory);
        }
        if ($filesystem->isDirectory($project . '/source')) {
            $filesystem->put($project . '/source/messages.json', '{"Hello":"Hello"}');
        }

        $arguments = ['--no-interaction' => true];
        if ($provideTarget) {
            $arguments['--target'] = $target;
        }
        $tester = runLocaleMigration($project, $arguments);

        expect($tester->getStatusCode())->toBe(Command::FAILURE)
            ->and(preg_replace('/\s+/', ' ', $tester->getErrorOutput()))->toContain($diagnostic)
            ->and($filesystem->exists($target))->toBeFalse();
    } finally {
        $filesystem->deleteDirectory($root);
    }
})->with([
    'no source directory'  => [['locales'], true, 'Translation directory does not exist'],
    'no locales directory' => [['source'], true, 'Translation directory does not exist'],
    'no target option'     => [['source', 'locales'], false, 'Provide a destination for locale directories with --target.'],
    'no locale files'      => [['source', 'locales'], true, 'No legacy locale files found'],
]);

function runLocaleMigration(string $project, array $arguments, array $answers = []): ApplicationTester
{
    $application = new Application;
    $application->setAutoExit(false);
    $application->addCommand(new MigrateLocalesCommand);

    $tester = new ApplicationTester($application);
    $tester->setInputs($answers);
    $tester->run(['command' => 'migrate-locales', 'project' => $project, ...$arguments], ['capture_stderr_separately' => true]);

    return $tester;
}
