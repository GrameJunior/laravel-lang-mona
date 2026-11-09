<?php

declare(strict_types=1);

use LaravelLang\Routes\Facades\LocalizationRoute;
use LaravelLang\Routes\Middlewares\LocalizationByCookie;
use LaravelLang\Routes\Middlewares\LocalizationByHeader;
use LaravelLang\Routes\Middlewares\LocalizationByModel;
use LaravelLang\Routes\Middlewares\LocalizationByParameter;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirect;
use LaravelLang\Routes\Middlewares\LocalizationBySession;
use Workbench\App\Http\Controllers\TestController;

app('router')
    ->middleware(LocalizationByParameter::class)
    ->get('path/{key}/{locale?}', TestController::class)
    ->name('via.parameter');

app('router')
    ->middleware(LocalizationByParameterWithRedirect::class)
    ->get('redirect/{key}/{locale?}', TestController::class)
    ->name('via.parameter.redirect');

app('router')
    ->middleware(LocalizationByParameterWithRedirect::class)
    ->get('not-named/redirect/{key}/{locale?}', TestController::class);

app('router')
    ->middleware(LocalizationByHeader::class)
    ->get('header/{key}', TestController::class)
    ->name('via.header');

app('router')
    ->middleware(LocalizationByCookie::class)
    ->get('cookie/{key}', TestController::class)
    ->name('via.cookie');

app('router')
    ->middleware(LocalizationBySession::class)
    ->get('session/{key}', TestController::class)
    ->name('via.session');

app('router')
    ->middleware(LocalizationByModel::class)
    ->get('model/default/{key}', TestController::class)
    ->name('via.model.default');

app('router')
    ->middleware(LocalizationByModel::class . ':foo')
    ->get('model/guard/{key}', TestController::class)
    ->name('via.model.guard');

app('router')
    ->middleware([
        LocalizationByParameter::class,
        LocalizationByParameterWithRedirect::class,
        LocalizationByHeader::class,
        LocalizationByCookie::class,
        LocalizationBySession::class,
        LocalizationByModel::class,
    ])
    ->get('clean/{key}', TestController::class)
    ->name('clean');

app('router')->localizedGroup(function () {
    app('router')
        ->middleware('web')
        ->get('group/macro/{key}', TestController::class)
        ->name('via.group.macro');
});

LocalizationRoute::group(function () {
    app('router')
        ->middleware('web')
        ->get('group/facade/{key}', TestController::class)
        ->name('via.group.facade');
});
