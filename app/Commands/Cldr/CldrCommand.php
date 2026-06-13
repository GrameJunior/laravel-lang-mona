<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Support\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Console\Command\Command;

use function array_key_first;
use function explode;
use function file_get_contents;
use function is_array;
use function is_file;
use function json_decode;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\task;
use function preg_match;
use function sprintf;

abstract class CldrCommand extends Command
{
    protected string $targetDirectory;

    protected string $fallback = 'en';

    protected array $aliases = [
        'pt_BR' => 'pt',
        'tl'    => 'fil',
        'zh_CN' => 'zh-Hans',
        'zh_HK' => 'zh-Hant-HK',
        'zh_TW' => 'zh-Hant',
    ];

    /** @var array<string, string>|null */
    private ?array $likelySubtags = null;

    public function __construct(
        protected Filesystem $filesystem = new Filesystem,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $name = $this->commandName();

        $who = Str::after($name, ':');

        $this->setName($name)
            ->setDescription("Update $who names from CLDR");
    }

    public function __invoke(): int
    {
        intro($this->commandName());

        foreach (Locale::options() as $name => $locale) {
            task($name, fn () => $this->handle($locale));
        }

        return self::SUCCESS;
    }

    abstract protected function perform(string $forCode, string $locale): array|string|null|Arrayable;

    protected function handle(string $locale): void
    {
        $data = [];

        foreach (Locale::values() as $current) {
            $result = $this->perform($locale, $current);

            $data[$current] = $result instanceof Arrayable ? $result->toArray() : $result;
        }

        $this->store($locale, $data);
    }

    protected function load(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException(sprintf('CLDR languages file not found: %s', $path));
        }

        $data = $this->filesystem->get($path);

        $key = array_key_first($data['main'] ?? []);

        $languages = $key === null ? null : ($data['main'][$key]['localeDisplayNames']['languages'] ?? null);

        if (! is_array($languages)) {
            throw new RuntimeException(sprintf('CLDR languages data not found: %s', $path));
        }

        return $languages;
    }

    protected function store(string $code, array $data): void
    {
        $this->filesystem->store(
            path     : $this->targetDirectory . '/' . $code . '.json',
            data     : $data,
            jsonFlags: JSON_PRETTY_PRINT
        );
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

    protected function bundleCode(string $locale): string
    {
        return $this->aliases[$locale] ?? $this->localeCode($locale);
    }

    protected function localeCode(string $locale): string
    {
        return str_replace('_', '-', $locale);
    }

    protected function baseCode(string $locale): string
    {
        return explode('-', $locale)[0];
    }

    protected function commandName(): string
    {
        return Str::of(static::class)
            ->classBasename()
            ->beforeLast('Command')
            ->snake(':')
            ->toString();
    }
}
