<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;

it('migrates one legacy project through the Toolkit executable', function (bool $interactive): void {
    $filesystem = new Filesystem;
    $root       = sys_get_temp_dir() . '/mona-migrate-' . bin2hex(random_bytes(8));
    $project    = $root . '/Legacy project';
    $target     = $root . '/New locales';
    $legacy     = $project . '/locales/fr';
    $locale     = $target . '/fr';

    try {
        $fixtures = [
            $project . '/source/messages.json'             => '{"greeting":"Hello","0":"0","invalid":"Invalid :ATTRIBUTE"}',
            $project . '/source/framework/12/messages.php' => '<?php return ["nested" => ["name" => "Name", "invalid" => "Missing :attribute", "plain" => "Plain"]];',
            $legacy . '/json.json'                         => '{"greeting":"Bonjour","0":"0","obsolete":"Ancien"}',
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

        $arguments = [PHP_BINARY, dirname(__DIR__, 3) . '/src/Toolkit/bin/toolkit', 'migrate-locales', $project];
        if (! $interactive) {
            $arguments[] = '--target=' . $target;
            $arguments[] = '--no-interaction';
        }

        $process = new Process($arguments);
        if ($interactive) {
            $process->setInput($target . PHP_EOL);
        }

        $process->run();

        expect($process->getExitCode())->toBe(Command::SUCCESS, $process->getErrorOutput() . $process->getOutput())
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

it('leaves the target unchanged when migration input is invalid', function (string $source, string $translation, string $diagnostic): void {
    $filesystem = new Filesystem;
    $root       = sys_get_temp_dir() . '/mona-migrate-' . bin2hex(random_bytes(8));
    $project    = $root . '/project';
    $target     = $root . '/target';
    $existing   = $target . '/de/default.json';

    try {
        $fixtures = [
            $project . '/source/messages.json' => $source,
            $project . '/locales/de/json.json' => '{"Hello":"Hallo"}',
            $project . '/locales/fr/json.json' => $translation,
            $existing                          => '{"Hello":"Guten Tag"}',
        ];

        foreach ($fixtures as $path => $content) {
            $filesystem->ensureDirectoryExists(dirname($path));
            $filesystem->put($path, $content);
        }

        $process = new Process([
            PHP_BINARY,
            dirname(__DIR__, 3) . '/src/Toolkit/bin/toolkit',
            'migrate-locales',
            $project,
            '--target=' . $target,
            '--no-interaction',
        ]);
        $process->run();

        expect($process->getExitCode())->toBe(Command::FAILURE)
            ->and($process->getErrorOutput())->toContain($diagnostic)
            ->and($filesystem->get($existing))->toBe($fixtures[$existing])
            ->and($filesystem->exists($target . '/fr'))->toBeFalse();
    } finally {
        $filesystem->deleteDirectory($root);
    }
})->with([
    'malformed locale JSON'    => ['{"Hello":"Hello"}', '{broken', 'Unable to read translation file'],
    'empty translation source' => ['{}', '{"Hello":"Bonjour"}', 'No translation sources found'],
]);
