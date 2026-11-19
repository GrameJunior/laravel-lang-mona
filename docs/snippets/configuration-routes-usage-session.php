<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationBySessionMiddleware;

app('router')
    ->middleware(LocalizationBySessionMiddleware::class)
    ->get('foo/{bar}', function (string $bar) {
        return response()->json([
            $bar => __('Foo'),
        ]);
    });
