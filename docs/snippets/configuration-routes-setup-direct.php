<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationByCookieMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByHeaderMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByModelMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirectMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationBySessionMiddleware;

app('router')
    ->middleware(LocalizationByParameterMiddleware::class)
    ->get('some/{locale}', fn () => view('welcome'));

app('router')
    ->middleware(LocalizationByParameterWithRedirectMiddleware::class)
    ->get('some/{locale?}', fn () => view('welcome'));

app('router')
    ->middleware(LocalizationByHeaderMiddleware::class)
    ->get('some', fn () => view('welcome'));

app('router')
    ->middleware(LocalizationByCookieMiddleware::class)
    ->get('some', fn () => view('welcome'));

app('router')
    ->middleware(LocalizationBySessionMiddleware::class)
    ->get('some', fn () => view('welcome'));

app('router')
    ->middleware(LocalizationByModelMiddleware::class)
    ->get('some', fn () => view('welcome'));
