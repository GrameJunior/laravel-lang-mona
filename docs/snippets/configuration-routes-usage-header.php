<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationByHeaderMiddleware;

app('router')
    ->middleware(LocalizationByHeaderMiddleware::class)
    ->get('foo/{bar}', function (string $bar) {
        return response()->json([
            $bar => __('Foo'),
        ]);
    });
