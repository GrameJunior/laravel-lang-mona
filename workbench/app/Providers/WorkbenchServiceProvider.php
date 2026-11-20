<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->setBootstrapPath();
        $this->setDatabasePath();
        $this->setLangPath();
    }

    public function boot(): void
    {
        $this->app['config']->set('auth.guards.foo', $this->app['config']->get('auth.guards.web'));

        Model::preventLazyLoading();
    }

    protected function setBootstrapPath(): void
    {
        $this->app->useBootstrapPath(
            $this->app->basePath('bootstrap/' . $this->parallelToken())
        );
    }

    protected function setDatabasePath(): void
    {
        $path = $this->app->joinPaths(
            $this->app->basePath('database'),
            $this->parallelToken()
        );

        $this->app->useDatabasePath($path);
    }

    protected function setLangPath(): void
    {
        $path = $this->app->joinPaths(
            $this->app->basePath('lang'),
            $this->parallelToken()
        );

        $this->app->useLangPath($path);
    }

    protected function parallelToken(): string
    {
        return ParallelTesting::token() ?: '0';
    }
}
