<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use RuntimeException;

use function array_key_first;
use function explode;
use function file_get_contents;
use function is_array;
use function is_file;
use function json_decode;
use function preg_match;
use function sprintf;

final class CldrCountryCommand extends CldrCommand
{
    protected string $targetDirectory = __DIR__ . '/../../../src/CountryNames/data';

    protected string $sourceDirectory = __DIR__ . '/../../../node_modules/cldr-localenames-full/main';

    protected string $likelySubtagsPath = __DIR__ . '/../../../node_modules/cldr-core/supplemental/likelySubtags.json';

    /** @var array<string, array<string, string>> */
    private array $territories = [];

    /** @var array<string, string>|null */
    private ?array $likelySubtags = null;

    protected function perform(string $forCode, string $locale): array
    {
        $code = $this->countryCode($locale);

        return [
            'code'      => $code,
            'native'    => $this->countryName($locale, $code),
            'localized' => $this->countryName($forCode, $code),
        ];
    }

    protected function countryCode(string $locale): string
    {
        $code = $this->localeCode($locale);

        foreach (explode('-', $code) as $part) {
            if (preg_match('/^[A-Z]{2}$|^[0-9]{3}$/', $part) === 1) {
                return $part;
            }
        }

        $likely = $this->likelySubtags()[$code]
            ?? $this->likelySubtags()[$this->baseCode($code)]
            ?? throw new RuntimeException(sprintf('Unable to find CLDR likely subtag for [%s].', $locale));

        if (preg_match('/-([A-Z]{2}|[0-9]{3})$/', $likely, $matches) === 1) {
            return $matches[1];
        }

        throw new RuntimeException(sprintf('Unable to find CLDR country code for [%s].', $locale));
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

    protected function likelySubtags(): array
    {
        if ($this->likelySubtags !== null) {
            return $this->likelySubtags;
        }

        if (! is_file($this->likelySubtagsPath)) {
            throw new RuntimeException(sprintf('CLDR likely subtags file not found: %s', $this->likelySubtagsPath));
        }

        $data = json_decode(file_get_contents($this->likelySubtagsPath), true, flags: JSON_THROW_ON_ERROR);

        $likelySubtags = $data['supplemental']['likelySubtags'] ?? null;

        if (! is_array($likelySubtags)) {
            throw new RuntimeException(sprintf('CLDR likely subtags data not found: %s', $this->likelySubtagsPath));
        }

        return $this->likelySubtags = $likelySubtags;
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
