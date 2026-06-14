<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Concerns;

use RuntimeException;

use function explode;
use function file_get_contents;
use function is_array;
use function is_file;
use function json_decode;
use function preg_match;
use function sprintf;

trait HasLocaleMetadata
{
    protected string $likelySubtagsPath = __DIR__ . '/../../node_modules/cldr-core/supplemental/likelySubtags.json';

    protected string $scriptMetadataPath = __DIR__ . '/../../node_modules/cldr-core/scriptMetadata.json';

    /** @var array<string, array<string, mixed>>|null */
    private ?array $scriptMetadata = null;

    protected function localeMetadata(string $locale): array
    {
        [$language, $script, $region] = $this->localeParts(
            $this->maximalLocaleCode($locale)
        );

        return [
            'regional'  => $language . '_' . $region,
            'type'      => $script,
            'direction' => $this->localeDirection($script),
        ];
    }

    protected function maximalLocaleCode(string $locale): string
    {
        $code = $this->localeCode($locale);

        if ($likely = $this->likelySubtags()[$code] ?? null) {
            return $likely;
        }

        [$language, $script, $region] = $this->localeParts($code, false);

        if ($script && $region) {
            return $code;
        }

        if ($script) {
            $likely = $this->likelySubtags()[$language . '-' . $script]
                ?? $this->likelySubtags()[$language]
                ?? null;

            if ($likely) {
                [, , $likelyRegion] = $this->localeParts($likely);

                return $language . '-' . $script . '-' . $likelyRegion;
            }
        }

        if ($region) {
            $likely = $this->likelySubtags()[$language . '-' . $region]
                ?? $this->likelySubtags()[$language]
                ?? null;

            if ($likely) {
                [, $likelyScript] = $this->localeParts($likely);

                return $language . '-' . $likelyScript . '-' . $region;
            }
        }

        throw new RuntimeException(sprintf('Unable to find CLDR likely subtag for [%s].', $locale));
    }

    protected function localeDirection(string $script): string
    {
        $rtl = $this->scriptMetadata()[$script]['rtl'] ?? null;

        if ($rtl === null) {
            throw new RuntimeException(sprintf('Unable to find CLDR script metadata for [%s].', $script));
        }

        return $rtl === 'YES' ? 'rtl' : 'ltr';
    }

    protected function scriptMetadata(): array
    {
        if ($this->scriptMetadata !== null) {
            return $this->scriptMetadata;
        }

        if (! is_file($this->scriptMetadataPath)) {
            throw new RuntimeException(sprintf('CLDR script metadata file not found: %s', $this->scriptMetadataPath));
        }

        $data = json_decode(file_get_contents($this->scriptMetadataPath), true, flags: JSON_THROW_ON_ERROR);

        $scriptMetadata = $data['scriptMetadata'] ?? null;

        if (! is_array($scriptMetadata)) {
            throw new RuntimeException(sprintf('CLDR script metadata not found: %s', $this->scriptMetadataPath));
        }

        return $this->scriptMetadata = $scriptMetadata;
    }

    protected function localeParts(string $locale, bool $full = true): array
    {
        $language = null;
        $script   = null;
        $region   = null;

        foreach (explode('-', $locale) as $index => $part) {
            if ($index === 0) {
                $language = $part;

                continue;
            }

            if (preg_match('/^[A-Z][a-z]{3}$/', $part) === 1) {
                $script = $part;

                continue;
            }

            if (preg_match('/^[A-Z]{2}$|^[0-9]{3}$/', $part) === 1) {
                $region = $part;
            }
        }

        if ($language && (! $full || ($script && $region))) {
            return [$language, $script, $region];
        }

        throw new RuntimeException(sprintf('Unable to parse CLDR locale code [%s].', $locale));
    }
}
