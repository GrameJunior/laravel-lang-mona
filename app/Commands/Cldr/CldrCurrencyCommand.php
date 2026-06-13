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

final class CldrCurrencyCommand extends CldrCommand
{
    protected string $targetDirectory = __DIR__ . '/../../../src/CurrencyNames/data';

    protected string $sourceDirectory = __DIR__ . '/../../../node_modules/cldr-numbers-full/main';

    protected string $currencyDataPath = __DIR__ . '/../../../node_modules/cldr-core/supplemental/currencyData.json';

    protected string $codeMappingsPath = __DIR__ . '/../../../node_modules/cldr-core/supplemental/codeMappings.json';

    protected string $likelySubtagsPath = __DIR__ . '/../../../node_modules/cldr-core/supplemental/likelySubtags.json';

    /** @var array<string, array<string, array<string, string>>> */
    private array $currencies = [];

    /** @var array<string, array<int, array<string, array<string, string>>>>|null */
    private ?array $regionCurrencies = null;

    /** @var array<string, array<string, string>>|null */
    private ?array $codeMappings = null;

    /** @var array<string, int> */
    private array $numericFallbacks = [
        'BYN' => 933,
        'SLE' => 925,
        'XAD' => 396,
        'XCG' => 532,
        'ZWG' => 924,
    ];

    protected function perform(string $forCode, string $locale): array
    {
        $code = $this->currencyCode($locale);

        return [
            'code'      => $code,
            'numeric'   => $this->numericCode($code),
            'native'    => $this->currencyName($locale, $code),
            'localized' => $this->currencyName($forCode, $code),
        ];
    }

    protected function currencyCode(string $locale): string
    {
        $country = $this->countryCode($locale);
        $code    = null;

        foreach ($this->regionCurrencies()[$country] ?? [] as $currency) {
            $current = array_key_first($currency);

            if ($current !== null && ! isset($currency[$current]['_to'])) {
                $code = $current;
            }
        }

        if ($code !== null) {
            return $code;
        }

        if ($country === '001') {
            return 'XXX';
        }

        throw new RuntimeException(sprintf('Unable to find CLDR currency code for [%s].', $locale));
    }

    protected function numericCode(string $code): int
    {
        $numeric = $this->codeMappings()[$code]['_numeric'] ?? $this->numericFallbacks[$code] ?? null;

        if ($numeric === null) {
            throw new RuntimeException(sprintf('Unable to find CLDR numeric code for currency [%s].', $code));
        }

        return (int) $numeric;
    }

    protected function currencyName(string $locale, string $code): string
    {
        $currencies = $this->currencies($locale);
        $fallback   = $this->currencies($this->fallback);

        return $currencies[$code]['displayName']
            ?? $fallback[$code]['displayName']
            ?? throw new RuntimeException(
                sprintf('Unable to find CLDR currency name for [%s] in [%s].', $code, $locale)
            );
    }

    protected function currencies(string $locale): array
    {
        $source = $this->bundleCode($locale);
        $path   = $this->sourceDirectory . '/' . $source . '/currencies.json';

        if (! is_file($path) && $source !== $this->baseCode($source)) {
            $source = $this->baseCode($source);
            $path   = $this->sourceDirectory . '/' . $source . '/currencies.json';
        }

        return $this->currencies[$source] ??= $this->loadCurrencies($path);
    }

    protected function regionCurrencies(): array
    {
        if ($this->regionCurrencies !== null) {
            return $this->regionCurrencies;
        }

        if (! is_file($this->currencyDataPath)) {
            throw new RuntimeException(sprintf('CLDR currency data file not found: %s', $this->currencyDataPath));
        }

        $data = json_decode(file_get_contents($this->currencyDataPath), true, flags: JSON_THROW_ON_ERROR);

        $regions = $data['supplemental']['currencyData']['region'] ?? null;

        if (! is_array($regions)) {
            throw new RuntimeException(sprintf('CLDR currency region data not found: %s', $this->currencyDataPath));
        }

        return $this->regionCurrencies = $regions;
    }

    protected function codeMappings(): array
    {
        if ($this->codeMappings !== null) {
            return $this->codeMappings;
        }

        if (! is_file($this->codeMappingsPath)) {
            throw new RuntimeException(sprintf('CLDR code mappings file not found: %s', $this->codeMappingsPath));
        }

        $data = json_decode(file_get_contents($this->codeMappingsPath), true, flags: JSON_THROW_ON_ERROR);

        $mappings = $data['supplemental']['codeMappings'] ?? null;

        if (! is_array($mappings)) {
            throw new RuntimeException(sprintf('CLDR code mappings data not found: %s', $this->codeMappingsPath));
        }

        return $this->codeMappings = $mappings;
    }

    protected function loadCurrencies(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException(sprintf('CLDR currencies file not found: %s', $path));
        }

        $data = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $key  = array_key_first($data['main'] ?? []);

        $currencies = $key === null ? null : ($data['main'][$key]['numbers']['currencies'] ?? null);

        if (! is_array($currencies)) {
            throw new RuntimeException(sprintf('CLDR currencies data not found: %s', $path));
        }

        return $currencies;
    }
}
