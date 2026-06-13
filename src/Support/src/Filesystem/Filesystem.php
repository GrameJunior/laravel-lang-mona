<?php

declare(strict_types=1);

namespace LaravelLang\Support\Filesystem;

use Brick\VarExporter\VarExporter;
use Illuminate\Filesystem\Filesystem as BaseFilesystem;
use RuntimeException;

use function file_exists;
use function json_encode;

final class Filesystem
{
    protected int $jsonOptions = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    public function __construct(
        protected BaseFilesystem $storage = new BaseFilesystem,
    ) {}

    public function get(string $path): array
    {
        if (! file_exists($path)) {
            return [];
        }

        return match ($this->storage->extension($path)) {
            'php'   => $this->storage->getRequire($path),
            'json'  => $this->storage->json($path),
            default => throw new RuntimeException('Unknown file extension for "' . $path . '"'),
        };
    }

    public function store(string $path, string|array $data, int $jsonFlags = 0): void
    {
        $content = match ($this->storage->extension($path)) {
            'php'  => VarExporter::export($data),
            'json' => json_encode($data, $this->jsonOptions | $jsonFlags),
        };

        $this->storage->put($path, $content);
    }

    public function directories(string $path): array
    {
        return $this->storage->directories($path);
    }

    public function deleteDirectory(string $path): void
    {
        $this->storage->deleteDirectory($path);
    }
}
