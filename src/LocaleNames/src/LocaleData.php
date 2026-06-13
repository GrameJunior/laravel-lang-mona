<?php

declare(strict_types=1);

namespace LaravelLang\LocaleNames;

use Illuminate\Contracts\Support\Arrayable;

final readonly class LocaleData implements Arrayable
{
    public function __construct(
        public string $code,
        public string $name,
    ) {}

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}
