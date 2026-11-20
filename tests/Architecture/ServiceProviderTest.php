<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;
use LaravelLang\Config\LangConfigServiceProvider;
use LaravelLang\JsonFallback\LangJsonFallbackServiceProvider;

$providers = [
    LangConfigServiceProvider::class,
    LangJsonFallbackServiceProvider::class,
];

arch('protect')
    ->expect($providers)
    ->toBeFinal();

arch('extend')
    ->expect($providers)
    ->toExtend(ServiceProvider::class);

arch('naming')
    ->expect($providers)
    ->toHavePrefix('Lang')
    ->toHaveSuffix('ServiceProvider');
