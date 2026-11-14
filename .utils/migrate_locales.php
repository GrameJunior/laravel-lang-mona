<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use LaravelLang\LocaleList\Locale;

require_once __DIR__ . '/../vendor/autoload.php';

$projects = [
    __DIR__ . '/../src/Actions',
    __DIR__ . '/../src/Attributes',
    __DIR__ . '/../src/HttpStatuses',
    __DIR__ . '/../src/MoonShine',
    __DIR__ . '/../src/StarterKits',
    __DIR__ . '/../src/Lang',
];

$target = __DIR__ . '/../src/Library/locales';

class Storage
{
    public function __construct(
        protected Filesystem $filesystem = new Filesystem,
        protected string $target = __DIR__ . '/../src/Library/locales',
    ) {}

    protected function store(string $path, array $content): void
    {
        $this->filesystem->put(
            $path,
            json_encode(
                $content,
                JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            )
        );
    }

    protected function readJson(string $path): array
    {
        if (! $this->filesystem->exists($path)) {
            return [];
        }

        return $this->filesystem->json($path);
    }

    protected function readPhp(string $path): array
    {
        if (! $this->filesystem->exists($path)) {
            return [];
        }

        return require $path;
    }
}

class MigrateExcludes extends Storage
{
    public function move(string $project): void
    {
        foreach (Locale::values() as $locale) {
            $this->filesystem->ensureDirectoryExists($this->target . '/' . $locale);

            $sourceContent = $this->readJson($project . '/locales/' . $locale . '/_excludes.json');
            $targetContent = $this->readJson($this->target($locale));

            $settings['excludes'] = (new Collection($targetContent['excludes'] ?? []))
                ->merge($sourceContent)
                ->unique(strict: true)
                ->sort()
                ->values()
                ->all();

            $this->store($this->target($locale), $settings);
        }
    }

    protected function target(string $locale): string
    {
        return $this->target . '/' . $locale . '/_settings.json';
    }
}

class MigrateTranslations extends Storage
{
    public function move(string $project, array $fromFiles, string $toFile): void
    {
        ['json' => $jsonSource, 'php' => $phpSource] = $this->loadSource($project);

        $inline = str_contains($toFile, 'inline');

        foreach (Locale::values() as $locale) {
            $result = $this->readJson($this->target($locale, $toFile));

            foreach ($fromFiles as $fromFile) {
                $translated = $this->readJson($project . '/locales/' . $locale . '/' . $fromFile);

                $handle = static function (array $array, array $translated, bool $isJson): array {
                    foreach ($array as $key => &$value) {
                        $translatedKey = $isJson ? $key : $value;

                        if (! isset($translated[$translatedKey])) {
                            unset($array[$translatedKey]);

                            continue;
                        }

                        $value = $translated[$translatedKey];
                    }

                    return $array;
                };

                $resultJson = $handle($jsonSource, $translated, true);
                $resultPhp  = $handle($phpSource, $translated, false);

                $result = array_merge($result, $resultJson, $resultPhp);
            }

            $result = (new Collection($result))
                ->filter(function (int|string|null $value, string $key) use ($inline): bool {
                    if (! $value) {
                        return false;
                    }

                    if (! $inline) {
                        return true;
                    }

                    if (str_contains(mb_strtolower($key), ':attribute')) {
                        return true;
                    }

                    return false;
                })
                ->unique(strict: true)
                ->sortKeys()
                ->all();

            $this->store($this->target($locale, $toFile), $result);
        }
    }

    protected function loadSource(string $project): array
    {
        $jsonSourceFiles = $this->filesystem->glob($project . '/source/{,*/,*/*/,*/*/*/}*/*.json', GLOB_BRACE);
        $phpSourceFiles  = $this->filesystem->glob($project . '/source/{,*/,*/*/,*/*/*/}*/*.php', GLOB_BRACE);

        $json = [];
        $php  = [];

        foreach ($jsonSourceFiles as $jsonSourceFile) {
            $json = array_merge($json, array_keys($this->readJson($jsonSourceFile)));
        }

        foreach ($phpSourceFiles as $phpSourceFile) {
            $array = $this->readPhp($phpSourceFile);

            $collection = (new Collection($array))
                ->dot()
                ->filter()
                ->flip()
                ->all();

            $php = array_merge($php, $collection);
        }

        return [
            'json' => (new Collection($json))
                ->unique(strict: true)
                ->flip()
                ->all(),

            'php' => (new Collection($php))
                ->unique(strict: true)
                ->all(),
        ];
    }

    protected function target(string $locale, string $filename): string
    {
        return $this->target . '/' . $locale . '/' . $filename;
    }
}

foreach ($projects as $project) {
    (new MigrateExcludes())->move($project);

    (new MigrateTranslations())->move($project, ['json.json', 'php.json'], 'default.json');
    (new MigrateTranslations())->move($project, ['json-inline.json', 'php-inline.json'], 'inline.json');

    (new Filesystem)->deleteDirectory($project . '/locales');
}
