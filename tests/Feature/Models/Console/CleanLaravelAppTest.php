<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

function withCleanModelApplication(Closure $scenario): void
{
    $project = dirname(__DIR__, 4);
    $path    = sys_get_temp_dir() . '/mona-models-' . Str::uuid();
    $files   = new Filesystem;

    try {
        $files->copyDirectory($project . '/tests/Fixtures/Models/LaravelApp', $path);
        $files->ensureDirectoryExists($path . '/app/Models');
        $files->ensureDirectoryExists($path . '/bootstrap/cache');
        $files->ensureDirectoryExists($path . '/database');
        $files->ensureDirectoryExists($path . '/vendor/composer');

        // Discover providers from the split package manifests using the installed test dependencies.
        $packages = array_map(
            static fn (string $package) => $files->json($project . '/src/' . $package . '/composer.json'),
            ['Config', 'Models']
        );
        $files->put($path . '/vendor/composer/installed.json', json_encode(['packages' => $packages], JSON_THROW_ON_ERROR));
        $files->put($path . '/vendor/autoload.php', <<<'PHP'
            <?php

            $loader = require getenv('MONA_TEST_AUTOLOAD');
            $loader->addPsr4('App\\', dirname(__DIR__) . '/app');
            PHP);

        $database = $path . '/database/database.sqlite';
        $files->put($database, '');

        $run = static fn (string ...$arguments): string => (new Process([PHP_BINARY, ...$arguments], $path, [
            'APP_ENV'             => 'local',
            'APP_LOCALE'          => 'en',
            'APP_FALLBACK_LOCALE' => 'de',
            'COMPOSER_VENDOR_DIR' => false,
            'DB_CONNECTION'      => 'sqlite',
            'DB_DATABASE'        => $database,
            'DB_FOREIGN_KEYS'    => 'true',
            'DB_URL'             => false,
            'MONA_TEST_AUTOLOAD' => $project . '/vendor/autoload.php',
            'VENDOR_PATH'        => false,
        ]))->mustRun()->getOutput();

        $scenario($path, $run);
    } finally {
        $files->deleteDirectory($path);
    }
}

test('a clean Laravel application discovers and publishes the localization configuration', function () {
    withCleanModelApplication(function (string $path, Closure $run) {
        $run('artisan', 'package:discover', '--no-interaction');
        $run('artisan', 'vendor:publish', '--tag=localization', '--no-interaction');

        foreach (['main', 'map', 'models', 'punctuation', 'routes'] as $name) {
            expect($path . '/config/localization_' . $name . '.php')->toBeReadableFile();
        }

        $config = $path . '/config/localization_models.php';
        $custom = '<?php return [\'filter\' => false];';
        file_put_contents($config, $custom);

        $run('artisan', 'vendor:publish', '--tag=localization', '--no-interaction');

        expect(file_get_contents($config))->toBe($custom);
    });
});

test('a clean Laravel application can generate, use and roll back a translation model', function () {
    withCleanModelApplication(function (string $path, Closure $run) {
        $run('artisan', 'make:model', 'Article', '--migration', '--no-interaction');
        $run('artisan', 'migrate', '--no-interaction');
        $run('artisan', 'make:model-translation', 'App\\Models\\Article', '--no-interaction');
        $run('artisan', 'migrate', '--no-interaction');

        $helper = $path . '/vendor/_laravel_lang/_ide_helper_models_' . hash('xxh128', 'App\\Models\\Article') . '.php';

        expect($helper)->toBeReadableFile();
        unlink($helper);

        $run('artisan', 'lang:models-helper', 'App\\Models\\Article', '--no-interaction');

        expect($helper)->toBeReadableFile();
        expect(file_get_contents($helper))->toContain('$title', '$description');

        $output = $run('exercise.php');

        expect($output)->toBeJson($output)->json()->toBe([
            'title'       => 'First article',
            'description' => 'Article description',
            'germanTitle' => 'Erster Artikel',
            'locales'     => ['de', 'en'],
            'uniquePair'  => true,
        ]);

        $run('artisan', 'migrate:rollback', '--no-interaction');

        $database = new PDO('sqlite:' . $path . '/database/database.sqlite');

        expect($database->query('SELECT COUNT(*) FROM articles')->fetchColumn())->toBe(2);
        expect($database->query("SELECT name FROM sqlite_master WHERE name = 'article_translations'")->fetchColumn())
            ->toBeFalse();
    });
});
