<?php

declare(strict_types=1);

// app/Http/Kernel.php

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use LaravelLang\Routes\Middlewares\LocalizationByCookieMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByHeaderMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByModelMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationByParameterWithRedirectMiddleware;
use LaravelLang\Routes\Middlewares\LocalizationBySessionMiddleware;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        // ...

        'localization.parameter' => LocalizationByParameterMiddleware::class,
        'localization.redirect'  => LocalizationByParameterWithRedirectMiddleware::class,
        'localization.header'    => LocalizationByHeaderMiddleware::class,
        'localization.cookie'    => LocalizationByCookieMiddleware::class,
        'localization.session'   => LocalizationBySessionMiddleware::class,
        'localization.model'     => LocalizationByModelMiddleware::class,
    ];
}
