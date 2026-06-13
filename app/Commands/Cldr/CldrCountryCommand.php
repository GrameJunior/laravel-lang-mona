<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use RuntimeException;

use function array_key_first;
use function file_get_contents;
use function is_array;
use function is_file;
use function json_decode;
use function sprintf;

final class CldrCountryCommand extends CldrCommand
{
    protected string $targetDirectory = __DIR__ . '/../../../src/CountryNames/data';

    protected string $sourceDirectory = __DIR__ . '/../../../node_modules/cldr-localenames-full/main';

    protected string $likelySubtagsPath = __DIR__ . '/../../../node_modules/cldr-core/supplemental/likelySubtags.json';

    /** @var array<string, array<string, string>> */
    private array $territories = [];

    protected function perform(string $forCode, string $locale): array
    {
        $code = $this->countryCode($locale);

        return [
            'code'      => $code,
            'native'    => $this->countryName($locale, $code),
            'localized' => $this->countryName($forCode, $code),
        ];
    }

    protected function countryName(string $locale, string $code): string
    {
        $territories = $this->territories($locale);
        $fallback    = $this->territories($this->fallback);

        return $territories[$code]
            ?? $fallback[$code]
            ?? throw new RuntimeException(
                sprintf('Unable to find CLDR country name for [%s] in [%s].', $code, $locale)
            );
    }

    protected function territories(string $locale): array
    {
        $source = $this->bundleCode($locale);
        $path   = $this->sourceDirectory . '/' . $source . '/territories.json';

        if (! is_file($path) && $source !== $this->baseCode($source)) {
            $source = $this->baseCode($source);
            $path   = $this->sourceDirectory . '/' . $source . '/territories.json';
        }

        return $this->territories[$source] ??= $this->loadTerritories($path);
    }

    protected function loadTerritories(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException(sprintf('CLDR territories file not found: %s', $path));
        }

        $data = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $key  = array_key_first($data['main'] ?? []);

        $territories = $key === null ? null : ($data['main'][$key]['localeDisplayNames']['territories'] ?? null);

        if (! is_array($territories)) {
            throw new RuntimeException(sprintf('CLDR territories data not found: %s', $path));
        }

        return $territories;
    }
}
