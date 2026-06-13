<?php

declare(strict_types=1);

use LaravelLang\Mona\Commands\Cldr\CldrCurrencyCommand;

it('builds currency payloads from CLDR data', function (): void {
    if (! is_file(__DIR__ . '/../../node_modules/cldr-core/supplemental/currencyData.json')) {
        $this->markTestSkipped('CLDR node dependencies are not installed.');
    }

    $command = new CldrCurrencyCommand();
    $perform = new ReflectionMethod($command, 'perform');

    expect($perform->invoke($command, 'ru', 'af'))->toBe([
        'code'      => 'ZAR',
        'numeric'   => 710,
        'native'    => 'Suid-Afrikaanse rand',
        'localized' => 'южноафриканский рэнд',
    ])->and($perform->invoke($command, 'ru', 'be'))->toBe([
        'code'      => 'BYN',
        'numeric'   => 933,
        'native'    => 'беларускі рубель',
        'localized' => 'белорусский рубль',
    ])->and($perform->invoke($command, 'ru', 'eo'))->toBe([
        'code'      => 'XXX',
        'numeric'   => 999,
        'native'    => 'nekonata monunuo',
        'localized' => 'неизвестная валюта',
    ]);
});
