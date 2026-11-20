<?php

declare(strict_types=1);

use LaravelLang\Routes\Middlewares\Middleware;
use Tests\Fixtures\ProjectNamespace;

arch('naming')
    ->expect(ProjectNamespace::All)
    ->classes()
    ->extending(Middleware::class)
    ->toHaveSuffix('Middleware');

arch('invokable')
    ->expect(ProjectNamespace::All)
    ->classes()
    ->extending(Middleware::class)
    ->toBeInvokable();
