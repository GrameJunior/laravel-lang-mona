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

$files = [
    'json.json'        => 'json-default.json',
    'json-inline.json' => 'json-default-inline.json',
    'php.json'         => 'php-default.json',
    'php-inline.json'  => 'php-default-inline.json',
];
// todo: куда-то потерялось много ключей. Например, "The provided password was incorrect."

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

        // todo теперь формируем массив переводов
        foreach (Locale::values() as $locale) {
            $result = $this->readJson($this->target($locale, $toFile));

            foreach ($fromFiles as $fromFile) {
                $translated = $this->readJson($project . '/locales/' . $locale . '/' . $fromFile);

                if (str_starts_with($fromFile, 'json')) {
                    foreach ($jsonSource as $value => $key) {
                        $result[$value] = $translated[$value] ?? $value;
                    }

                    continue;
                }

                foreach ($phpSource as $value => $key) {
                    $result[$value] = $translated[$key] ?? $value;
                }
            }

            $result = (new Collection($result))
                ->filter()
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
            $json += array_keys($this->readJson($jsonSourceFile));
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
}

return;
/*
 * Задумка следующая:
 * Сейчас файлы локализаций, по сути, жёстко привязаны к ключам.
 * Хочу сделать универсальное средство.
 * 
 * В качестве ключа должна быть переведённая фраза на английском, а в качестве значения - на нужном языке.
 * С учётом стилизаций, конечные файлы будут иметь вид:
 * 
 * locales/
 *   ru/
 *     _settings.json
 *     default.json
 *     inline.json
 *     personal.json
 *     funny.json
 * 
 * Таким образом, мы избавимся от зоопарка inline переводов, и их не нужно будет поддерживать для каждого вида перевода.
 * А также _excludes мигрирует в _settings, дабы также не плодить сущности ради сущностей.
 * 
 * Вследствие этого удаляем параметр `inline` в конфигах, заменив его на стиль перевода.
 */
