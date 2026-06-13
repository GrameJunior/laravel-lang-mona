<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use RuntimeException;

use function sprintf;

final class CldrLocaleCommand extends CldrCommand
{
    protected string $targetDirectory = __DIR__ . '/../../../src/LocaleNames/data';

    protected string $sourceDirectory = __DIR__ . '/../../../node_modules/cldr-localenames-full/main';

    /** @var array<string, array<string, string>> */
    private array $languages = [];

    protected function perform(string $forCode, string $locale): string
    {
        $forLanguages = $this->languages($forCode);
        $fallback     = $this->languages($this->fallback);

        $code = $this->localeCode($locale);
        $base = $this->baseCode($code);

        return $forLanguages[$code]
            ?? $forLanguages[$base]
            ?? $fallback[$code]
            ?? $fallback[$base]
            ?? throw new RuntimeException(
                sprintf('Unable to find CLDR locale name for [%s] in [%s].', $locale, $forCode)
            );
    }

    protected function languages(string $locale): array
    {
        $source = $this->bundleCode($locale);

        return $this->languages[$source] ??= $this->load(
            $this->sourceDirectory . '/' . $source . '/languages.json'
        );
    }
}
