<?php

declare(strict_types=1);

// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use LaravelLang\Routes\Middlewares\LocalizationByCookieMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByHeaderMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByModelMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirectMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationBySessionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'localization.parameter' => LocalizationByParameterMiddleware::class,
            'localization.redirect'  => LocalizationByParameterWithRedirectMiddleware::class,
            'localization.header'    => LocalizationByHeaderMiddleware::class,
            'localization.cookie'    => LocalizationByCookieMiddleware::class,
            'localization.session'   => LocalizationBySessionMiddleware::class,
            'localization.model'     => LocalizationByModelMiddleware::class,
        ]);
    });
