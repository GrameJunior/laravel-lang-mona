<?php

declare(strict_types=1);

arch('without code')
    ->expect([
        'LaravelLang\Actions',
        'LaravelLang\Attributes',
        'LaravelLang\Common',
        'LaravelLang\HttpStatuses',
        'LaravelLang\Lang',
        'LaravelLang\Library',
    ])
    ->not->toBeClass()
    ->not->toBeTrait()
    ->not->toBeEnum()
    ->not->toBeInterface();
