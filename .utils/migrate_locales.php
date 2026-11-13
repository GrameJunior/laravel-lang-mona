<?php

declare(strict_types=1);

use Illuminate\Support\Arr;
use LaravelLang\LocaleList\Locale;

require_once __DIR__ . '/../vendor/autoload.php';

$projects = [
    __DIR__ . '/../src/Actions/locales',
    __DIR__ . '/../src/Attributes/locales',
    __DIR__ . '/../src/HttpStatuses/locales',
    __DIR__ . '/../src/MoonShine/locales',
    __DIR__ . '/../src/StarterKits/locales',
    __DIR__ . '/../src/Lang/locales',
];

$target = __DIR__ . '/../src/Library/locales';

$files = [
    '_excludes.json'   => '_excludes.json',
    'json.json'        => 'json-default.json',
    'json-inline.json' => 'json-default-inline.json',
    'php.json'         => 'php-default.json',
    'php-inline.json'  => 'php-default-inline.json',
];

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

foreach (Locale::values() as $locale) {
    dump($locale . '...');

    foreach ($files as $fromFilename => $toFilename) {
        $result = [];

        foreach ($projects as $project) {
            $sourcePath = $project . "/" . $locale . "/" . $fromFilename;
            $targetPath = $target . "/" . $locale . "/" . $toFilename;

            if (! file_exists($sourcePath)) {
                continue;
            }

            $content = json_decode(file_get_contents($sourcePath), true);

            if (str_starts_with($fromFilename, 'php')) {
                $content = Arr::dot($content);
            }

            if ($content) {
                $result = array_merge($result[$fromFilename] ?? [], $content);
            }
        }

        if (! is_dir($target . "/" . $locale)) {
            mkdir($target . "/" . $locale, 0777, true);
        }

        ksort($result);

        if (str_starts_with($fromFilename, 'php') && $result) {
            $result = Arr::undot($result);
        }

        if ($result) {
            file_put_contents(
                $targetPath,
                json_encode(
                    $result,
                    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                )
            );
        }
    }
}
