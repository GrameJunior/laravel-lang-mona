<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands;

use Illuminate\Support\Collection;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Support\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;

use function file_exists;
use function Laravel\Prompts\task;
use function realpath;
use function sprintf;

#[AsCommand('library:collect', 'Collect library data')]
class LibraryCommand extends Command
{
    protected string $sourceDirectory = __DIR__ . '/../../src/';

    protected string $targetDirectory = __DIR__ . '/../../src/Library/locales';

    protected array $files = [
        'json',
        'php',
    ];

    protected array $styles = [
        'formal',
        'informal',
    ];

    protected array $inlines = [
        '',
        '-inline',
    ];

    public function __construct(
        protected Filesystem $filesystem = new Filesystem,
    ) {
        parent::__construct();
    }

    public function __invoke(
        #[Option('Clear source directories')]
        bool $purge = false,
    ): int {
        foreach (Locale::options() as $name => $locale) {
            task($name, fn () => $this->handle($locale, $purge));
        }

        return self::SUCCESS;
    }

    protected function handle(string $locale, bool $purge): void
    {
        //$json       = [];
        //$jsonInline = [];
        //$php        = [];
        //$phpInline  = [];
        //$excludes   = [];
        //
        //foreach ($this->directories() as $directory) {
        //    $json = array_merge($json, $this->load($directory, $locale, 'json'));
        //    $php  = array_merge($php, $this->load($directory, $locale, 'php'));
        //
        //    $jsonInline = array_merge($jsonInline, $this->load($directory, $locale, 'json-inline'));
        //    $phpInline  = array_merge($phpInline, $this->load($directory, $locale, 'php-inline'));
        //
        //    $excludes = array_merge($excludes, $this->load($directory, $locale, '_excludes'));
        //
        //    $this->clear($directory, $locale, $purge);
        //}
        //
        //$this->store($locale, 'json', $json);
        //$this->store($locale, 'json-inline', $jsonInline);
        //
        //$this->store($locale, 'php', $php);
        //$this->store($locale, 'php-inline', $phpInline);
        //
        //$this->store($locale, '_excludes', $excludes);
        ///

        $files = $this->mapFiles();

        $this->exportFiles($files);
    }

    protected function mapFiles(): Collection
    {
        return (new Collection($this->files))->crossJoin(
            $this->styles,
            $this->inlines,
        );
    }

    protected function exportFiles(Collection $files): void
    {
        $files
            ->each(function (array $file) {
                $export = [];

                foreach ($this->directories() as $directory) {

                }
            });
    }

    protected function directories(): array
    {
        return (new Collection($this->filesystem->directories($this->sourceDirectory)))
            ->filter(static fn (string $path) => file_exists($path . '/laravel_lang.yaml'))
            ->map(static fn (string $path) => realpath($path . '/locales'))
            ->all();
    }

    protected function load(string $path, string $locale, string $type): array
    {
        return $this->filesystem->get(
            sprintf('%s/%s/%s.json', $path, $locale, $type)
        );
    }

    protected function store(string $locale, string $type, array $data): void
    {
        $path = sprintf('%s/%s/%s.json', $this->targetDirectory, $locale, $type);

        $this->filesystem->store($path, $data, JSON_PRETTY_PRINT);
    }

    protected function clear(string $path, string $locale, bool $purge): void
    {
        if (! $purge) {
            return;
        }

        $this->filesystem->deleteDirectory(
            $path . '/' . $locale
        );
    }
}
