<?php

declare(strict_types=1);

namespace LaravelLang\CurrencyNames\Data;

use Illuminate\Contracts\Support\Arrayable;

final readonly class CurrencyData implements Arrayable
{
    public function __construct(
        public string $code,
        public ?int $numeric,
        public string $native,
        public string $localized,
    ) {}

    public function toArray(): array
    {
        return [
            'code'      => $this->code,
            'numeric'   => $this->numeric,
            'native'    => $this->native,
            'localized' => $this->localized,
        ];
    }
}
