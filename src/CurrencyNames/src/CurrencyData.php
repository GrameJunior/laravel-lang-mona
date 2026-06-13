<?php

declare(strict_types=1);

namespace LaravelLang\CurrencyNames;

use Illuminate\Contracts\Support\Arrayable;

final readonly class CurrencyData implements Arrayable
{
    public function __construct(
        public string $code,
        public int $numeric,
        public string $name,
    ) {}

    public function toArray(): array
    {
        return [
            'code'    => $this->code,
            'numeric' => $this->numeric,
            'name'    => $this->name,
        ];
    }
}
