<?php

declare(strict_types=1);

use LaravelLang\JsonFallback\OverridedTranslator;

arch('protect')
    ->expect(OverridedTranslator::class)
    ->toBeFinal();

arch('naming')
    ->expect(OverridedTranslator::class)
    ->toHaveSuffix('Translator');
