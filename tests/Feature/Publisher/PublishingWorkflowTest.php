<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\Publisher\Collectors\PluginCollector;

use function Pest\Laravel\artisan;

beforeEach(function () {
    $this->publisherRoot = sys_get_temp_dir() . '/mona-publisher-' . bin2hex(random_bytes(8));

    app()->setBasePath($this->publisherRoot);
    app()->bind(PluginCollector::class);

    config()->set([
        'app.locale'                => 'fr',
        'app.fallback_locale'       => 'de',
        'localization_main.aliases' => [],
        'localization_main.styles'  => ['default'],
    ]);
});

afterEach(function () {
    File::deleteDirectory($this->publisherRoot);
});

describe('removing locales', function () {
    beforeEach(function () {
        config()->set('localization_main.aliases', ['sv' => 'sv_SE', 'fr' => 'fr_FR']);

        writePublisherPackage('tests/translations', [], [
            'messages.php'  => '{locale}/messages.php',
            'messages.json' => '{locale}.json',
            'vendor.php'    => 'vendor/demo/{locale}/messages.php',
            'vendor.json'   => 'vendor/demo/{locale}.json',
        ], [
            'source/messages.php'    => ['greeting' => 'Hello'],
            'source/messages.json'   => ['Hello' => 'Hello'],
            'source/vendor.php'      => ['greeting' => 'Hello'],
            'source/vendor.json'     => ['Hello' => 'Hello'],
            'locales/sv/default.json' => ['Hello' => 'Hej'],
        ]);
    });

    test('removes application and vendor translations by code or alias', function (string $add, string $remove) {
        artisan('lang:add', ['locales' => [$add, 'en']])->assertSuccessful()->run();
        artisan('lang:update')->assertSuccessful()->run();

        [$php, $json, $vendorPhp, $vendorJson] = publisherTranslationPaths('sv');
        expect(File::getRequire($php))->toBe(['greeting' => 'Hej'])
            ->and(File::json($json))->toBe(['Hello' => 'Hej'])
            ->and(File::getRequire($vendorPhp))->toBe(['greeting' => 'Hej'])
            ->and(File::json($vendorJson))->toBe(['Hello' => 'Hej']);

        foreach (publisherTranslationPaths('sv_SE') as $path) {
            expect($path)->not->toBeFile();
        }

        expect(lang_path('sv_SE'))->not->toBeDirectory()
            ->and(lang_path('vendor/demo/sv_SE'))->not->toBeDirectory();

        Locales::set('sv_SE');
        expect(__('messages.greeting'))->toBe('Hej')
            ->and(__('Hello'))->toBe('Hej');
        Locales::set('fr');

        config()->set('localization_main.aliases.sv', 'sv-SE');

        $unrelated = lang_path('sv-SE/keep.txt');
        File::ensureDirectoryExists(dirname($unrelated));
        File::put($unrelated, 'Keep this file');

        $retained = [
            ...publisherTranslationPaths('fr'),
            ...publisherTranslationPaths('de'),
            ...publisherTranslationPaths('en'),
            $unrelated,
        ];
        $contents = array_map(File::get(...), $retained);

        artisan('lang:rm', ['locales' => [$remove, 'fr', 'de']])
            ->expectsOutputToContain('DONE')
            ->assertSuccessful()
            ->run();

        foreach (publisherTranslationPaths('sv') as $path) {
            expect($path)->not->toBeFile();
        }

        expect(lang_path('sv'))->not->toBeDirectory()
            ->and(lang_path('vendor/demo/sv'))->not->toBeDirectory()
            ->and(array_map(File::get(...), $retained))->toBe($contents);
    })->with([
        'from code to updated alias' => ['sv', 'sv-SE'],
        'from alias to code'         => ['sv_SE', 'sv'],
    ]);

    test('keeps the vendor directory when a locale alias matches its name', function () {
        artisan('lang:add', ['locales' => ['sv', 'en']])->assertSuccessful()->run();

        $path = lang_path('vendor/demo/de/messages.php');
        $contents = File::get($path);

        config()->set('localization_main.aliases.sv', 'vendor');

        artisan('lang:rm', ['locales' => ['sv']])->assertSuccessful()->run();

        expect(lang_path('sv'))->not->toBeDirectory()
            ->and(File::get($path))->toBe($contents);
    });

    test('protects the current locale when its code is also an alias', function () {
        artisan('lang:add', ['locales' => ['sv']])->assertSuccessful()->run();
        $paths = publisherTranslationPaths('fr');
        $contents = array_map(File::get(...), $paths);

        config()->set('localization_main.aliases', ['fr' => 'fr_FR', 'sv' => 'fr']);

        artisan('lang:rm', ['locales' => ['fr_FR']])
            ->expectsOutputToContain('PROTECTED')
            ->assertSuccessful()
            ->run();

        expect(array_map(File::get(...), $paths))->toBe($contents);
    });

    test('leaves translations alone for an unknown code', function () {
        artisan('lang:add', ['locales' => ['en']])->assertSuccessful()->run();

        $paths = publisherTranslationPaths('en');
        $contents = array_map(File::get(...), $paths);

        artisan('lang:rm', ['locales' => ['not-a-locale']])
            ->expectsOutputToContain('INVALID')
            ->assertSuccessful()
            ->run();

        expect(array_map(File::get(...), $paths))->toBe($contents);
    });

    test('skips removing a locale twice', function () {
        artisan('lang:add', ['locales' => ['sv']])->assertSuccessful()->run();
        artisan('lang:rm', ['locales' => ['sv']])->assertSuccessful()->run();
        artisan('lang:rm', ['locales' => ['sv_SE']])
            ->expectsOutputToContain('SKIP')
            ->assertSuccessful()
            ->run();

        foreach (publisherTranslationPaths('sv') as $path) {
            expect($path)->not->toBeFile();
        }
    });

    test('asks before removing all unprotected locales', function (string $confirmation) {
        artisan('lang:add', ['locales' => ['en', 'sv']])->assertSuccessful()->run();

        $protected = [...publisherTranslationPaths('fr'), ...publisherTranslationPaths('de')];
        $protectedContents = array_map(File::get(...), $protected);
        $optional = [...publisherTranslationPaths('en'), ...publisherTranslationPaths('sv')];
        $optionalContents = array_map(File::get(...), $optional);

        artisan('lang:rm')
            ->expectsConfirmation('Do you want to remove all localizations?', $confirmation)
            ->assertSuccessful()
            ->run();

        expect(array_map(File::get(...), $protected))->toBe($protectedContents);

        if ($confirmation === 'yes') {
            foreach ($optional as $path) {
                expect($path)->not->toBeFile();
            }
            expect(lang_path('en'))->not->toBeDirectory()
                ->and(lang_path('sv'))->not->toBeDirectory();
        } else {
            expect(array_map(File::get(...), $optional))->toBe($optionalContents);
        }
    })->with(['yes', 'no']);

    test('keeps localizations when run without interactive confirmation', function () {
        artisan('lang:add', ['locales' => ['sv']])->assertSuccessful()->run();

        $paths = publisherTranslationPaths('sv');
        $contents = array_map(File::get(...), $paths);

        $this->withoutMockingConsoleOutput();

        expect(artisan('lang:rm', ['--no-interaction' => true]))->toBe(0)
            ->and(array_map(File::get(...), $paths))->toBe($contents);
    });
});

describe('updating locales from installed manifests', function () {
    beforeEach(function () {
        writePublisherPackage('tests/compatible', ['vendor' => 'composer/semver', 'version' => '^3.6'], [
            'messages.php'  => '{locale}/messages.php',
            'messages.json' => '{locale}.json',
        ], [
            'source/messages.php'    => ['nested' => ['greeting' => 'Hello', 'farewell' => 'Goodbye']],
            'source/messages.json'   => ['Hello' => 'Hello', 'Goodbye' => 'Goodbye'],
            'locales/fr/default.json' => ['Hello' => 'From the package'],
        ]);

        writePublisherPackage('tests/incompatible', ['vendor' => 'composer/semver', 'version' => '^999.0'], [
            'incompatible.php' => '{locale}/incompatible.php',
            'skip-source.php'  => '{locale}/skip-source.php',
            'skip-target.php'  => '{locale}/skip-target.php',
        ], [
            'source/incompatible.php' => ['status' => 'Forced incompatible package'],
            'source/skip-source.php'  => ['status' => 'Skipped source'],
            'source/skip-target.php'  => ['status' => 'Skipped target'],
        ]);

        writePublisherPackage('tests/missing', ['vendor' => 'tests/absent-dependency'], [
            'missing.php' => '{locale}/missing.php',
        ], [
            'source/missing.php' => ['status' => 'Forced absent package'],
        ]);

        writePublisherFiles([
            base_path('vendor/laravel-lang/library/locales/fr/default.json') => [
                'Hello'   => 'From the library',
                'Goodbye' => 'Library farewell',
            ],
            lang_path('fr/messages.php') => ['nested' => ['custom' => 'Custom PHP']],
            lang_path('fr.json')         => ['Custom key' => 'Custom JSON'],
        ]);
    });

    test('merges compatible translations and writes the same files on repeat', function () {
        artisan('lang:update')->assertSuccessful()->run();

        expect(File::getRequire(lang_path('fr/messages.php')))->toEqual([
            'nested' => [
                'greeting' => 'From the package',
                'farewell' => 'Library farewell',
                'custom'   => 'Custom PHP',
            ],
        ])->and(File::json(lang_path('fr.json')))->toEqual([
            'Hello'      => 'From the package',
            'Goodbye'    => 'Library farewell',
            'Custom key' => 'Custom JSON',
        ])->and(lang_path('fr/incompatible.php'))->not->toBeFile()
            ->and(lang_path('fr/missing.php'))->not->toBeFile();

        $paths = [lang_path('fr/messages.php'), lang_path('fr.json'), lang_path('de/messages.php'), lang_path('de.json')];
        $contents = array_map(File::get(...), $paths);

        artisan('lang:update')->assertSuccessful()->run();

        expect(array_map(File::get(...), $paths))->toBe($contents);
    });

    test('forces missing and incompatible dependencies while respecting source and target skips', function () {
        config()->set([
            'localization_main.force' => ['composer/semver', 'tests/absent-dependency'],
            'localization_main.skip'  => ['*/tests/incompatible/source/skip-source.php', '*/lang/*/skip-target.php'],
        ]);

        $skippedTarget = lang_path('fr/skip-target.php');
        writePublisherFiles([$skippedTarget => ['status' => 'Custom target']]);
        $originalTarget = File::get($skippedTarget);

        artisan('lang:update')->assertSuccessful()->run();

        expect(File::getRequire(lang_path('fr/incompatible.php')))->toBe(['status' => 'Forced incompatible package'])
            ->and(File::getRequire(lang_path('fr/missing.php')))->toBe(['status' => 'Forced absent package'])
            ->and(lang_path('fr/skip-source.php'))->not->toBeFile()
            ->and(File::get($skippedTarget))->toBe($originalTarget);
    });
});

function publisherTranslationPaths(string $locale): array
{
    return [
        lang_path("$locale/messages.php"),
        lang_path("$locale.json"),
        lang_path("vendor/demo/$locale/messages.php"),
        lang_path("vendor/demo/$locale.json"),
    ];
}

function writePublisherPackage(string $package, array $requirements, array $targets, array $files): void
{
    $entries = [];

    foreach ($targets as $source => $target) {
        $entries[] = ['source' => $source, 'target' => $target];
    }

    $manifest = ['plugins' => [$requirements + ['files' => $entries]]];
    foreach (['laravel_lang.json' => $manifest] + $files as $path => $content) {
        writePublisherFiles([base_path("vendor/$package/$path") => $content]);
    }
}

function writePublisherFiles(array $files): void
{
    foreach ($files as $path => $content) {
        File::ensureDirectoryExists(dirname($path));
        File::put($path, str_ends_with($path, '.json')
            ? json_encode($content, JSON_THROW_ON_ERROR)
            : '<?php return ' . var_export($content, true) . ';');
    }
}
