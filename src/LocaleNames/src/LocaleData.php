<?php

declare(strict_types=1);

namespace LaravelLang\LocaleNames;

use Illuminate\Contracts\Support\Arrayable;

final readonly class LocaleData implements Arrayable
{
    public function __construct(
        public string $code,
        public string $name,
        public ?string $regional = null,
        public ?string $type = null,
        public ?string $direction = null,
    ) {}

    public function toArray(): array
    {
        return [
            'code'      => $this->code,
            'name'      => $this->name,
            'regional'  => $this->regional,
            'type'      => $this->type,
            'direction' => $this->direction,
        ];
    }
}
