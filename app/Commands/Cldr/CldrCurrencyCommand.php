<?php

declare(strict_types=1);

namespace LaravelLang\Mona\Commands\Cldr;

use Illuminate\Contracts\Support\Arrayable;
use LaravelLang\CurrencyNames\CurrencyData;
use LaravelLang\Mona\Concerns\HasCurrencies;

final class CldrCurrencyCommand extends CldrCommand
{
    use HasCurrencies;

    protected string $targetDirectory = __DIR__ . '/../../../src/CurrencyNames/data';

    protected function perform(string $forCode, string $locale): Arrayable
    {
        $code = $this->currencyCode($locale);

        return new CurrencyData(
            code   : $code,
            numeric: $this->numericCode($code),
            name   : $this->currencyName($forCode, $code),
        );
    }
}
