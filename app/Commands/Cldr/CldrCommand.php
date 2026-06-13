<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use Illuminate\Support\Str;
use LaravelLang\LocaleList\Locale;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function array_key_first;
use function env;
use function file_get_contents;
use function file_put_contents;
use function is_array;
use function is_file;
use function json_decode;
use function json_encode;
use function Laravel\Prompts\task;
use function sprintf;

abstract class CldrCommand extends Command
{
    protected string $targetDirectory;

    protected int $jsonOptions = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    protected function configure(): void
    {
        $name = $this->commandName();

        $who = Str::after($name, ':');

        $this->setName($name)
            ->setDescription("Update $who names from CLDR");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (Locale::options() as $name => $locale) {
            task($name, fn () => $this->handle($locale));
        }

        return self::SUCCESS;
    }

    abstract protected function perform(string $forCode, string $locale): array|string|null;

    protected function handle(string $locale): void
    {
        $data = [];

        foreach (Locale::values() as $current) {
            $data[$current] = $this->perform($locale, $current);
        }

        $this->store($locale, $data);
    }

    protected function load(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException(sprintf('CLDR languages file not found: %s', $path));
        }

        $data = json_decode(file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        $key  = array_key_first($data['main'] ?? []);

        $languages = $key === null ? null : ($data['main'][$key]['localeDisplayNames']['languages'] ?? null);

        if (! is_array($languages)) {
            throw new RuntimeException(sprintf('CLDR languages data not found: %s', $path));
        }

        return $languages;
    }

    protected function store(string $code, array $data): void
    {
        file_put_contents($this->targetDirectory . '/' . $code . '.json', $this->encode($data));
    }

    protected function encode(array $data): string
    {
        return env('PRETTY_JSON')
            ? json_encode($data, $this->jsonOptions | JSON_PRETTY_PRINT)
            : json_encode($data, $this->jsonOptions);
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
