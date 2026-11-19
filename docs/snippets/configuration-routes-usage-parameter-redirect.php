<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirectMiddleware;

app('router')
    ->middleware(LocalizationByParameterWithRedirectMiddleware::class)
    ->get('some/{foo}/bar/{locale?}', function (string $foo) {
        return response()->json([
            $foo => __('Foo'),
        ]);
    });
