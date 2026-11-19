<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationByParameterMiddleware;

app('router')
    ->middleware(LocalizationByParameterMiddleware::class)
    ->get('foo/{locale}/{bar}', function (string $bar) {
        return response()->json([
            $bar => __('Foo'),
        ]);
    });
