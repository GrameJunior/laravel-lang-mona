<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationByCookieMiddleware;

app('router')
    ->middleware(LocalizationByCookieMiddleware::class)
    ->get('foo/{bar}', function (string $bar) {
        return response()->json([
            $bar => __('Foo'),
        ]);
    });
