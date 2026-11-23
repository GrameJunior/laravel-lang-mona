<?php

declare(strict_types=1);

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Facade;
use LaravelLang\Config\LangConfigServiceProvider;
use LaravelLang\JsonFallback\LangJsonFallbackServiceProvider;
use LaravelLang\JsonFallback\OverridedTranslator;
use LaravelLang\LocaleList\Direction;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Models\HasTranslations;
use LaravelLang\Publisher\Contracts\Decorator;
use Tests\Fixtures\ProjectNamespace;

arch('private methods')
    ->expect(ProjectNamespace::All)
    ->not->toHavePrivateMethods();

arch('traits')
    ->expect(ProjectNamespace::All)
    ->traits()
    ->toExtendNothing();

arch('class')
    ->expect(ProjectNamespace::All)
    ->toBeClass()
    ->ignoring([
        'LaravelLang\Routes\localizedRoute',
        'LaravelLang\Models\Concerns',
        'LaravelLang\LocaleList\Concerns',
        Decorator::class,
        Direction::class,
        HasTranslations::class,
        Locale::class,
    ]);

arch('final')
    ->expect(ProjectNamespace::All)
    ->classes()
    ->not->extending(Facade::class)
    ->not->implementing(CastsAttributes::class)
    ->not->toBeFinal()
    ->ignoring([
        'LaravelLang\Config\Constants',
        'LaravelLang\Exceptions',
        'LaravelLang\Models\Eloquent\Scopes',
        LangConfigServiceProvider::class,
        LangJsonFallbackServiceProvider::class,
        OverridedTranslator::class,
    ]);
