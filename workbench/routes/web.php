<?php

declare(strict_types=1);

use LaravelLang\Routes\Facades\LocalizationRoute;
use LaravelLang\Routes\Middlewares\LocalizationByCookieMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByHeaderMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByModelMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirectMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationBySessionMiddleware;
use Workbench\App\Http\Controllers\TestController;

app('router')
    ->middleware(LocalizationByParameterMiddleware::class)
    ->get('path/{key}/{locale?}', TestController::class)
    ->name('via.parameter');

app('router')
    ->middleware(LocalizationByParameterWithRedirectMiddleware::class)
    ->get('redirect/{key}/{locale?}', TestController::class)
    ->name('via.parameter.redirect');

app('router')
    ->middleware(LocalizationByParameterWithRedirectMiddleware::class)
    ->get('not-named/redirect/{key}/{locale?}', TestController::class);

app('router')
    ->middleware(LocalizationByHeaderMiddleware::class)
    ->get('header/{key}', TestController::class)
    ->name('via.header');

app('router')
    ->middleware(LocalizationByCookieMiddleware::class)
    ->get('cookie/{key}', TestController::class)
    ->name('via.cookie');

app('router')
    ->middleware(LocalizationBySessionMiddleware::class)
    ->get('session/{key}', TestController::class)
    ->name('via.session');

app('router')
    ->middleware(LocalizationByModelMiddleware::class)
    ->get('model/default/{key}', TestController::class)
    ->name('via.model.default');

app('router')
    ->middleware(LocalizationByModelMiddleware::class . ':foo')
    ->get('model/guard/{key}', TestController::class)
    ->name('via.model.guard');

app('router')
    ->middleware([
        LocalizationByParameterMiddleware::class,
        LocalizationByParameterWithRedirectMiddleware::class,
        LocalizationByHeaderMiddleware::class,
        LocalizationByCookieMiddleware::class,
        LocalizationBySessionMiddleware::class,
        LocalizationByModelMiddleware::class,
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
