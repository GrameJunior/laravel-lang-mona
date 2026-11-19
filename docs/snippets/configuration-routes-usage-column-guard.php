<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\LocalizationByModelMiddleware;

app('router')
    ->middleware(LocalizationByModelMiddleware::class . ':guard_name')
    ->get('foo/{bar}', function (string $bar) {
        return response()->json([
            $bar => __('Foo'),
        ]);
    });
