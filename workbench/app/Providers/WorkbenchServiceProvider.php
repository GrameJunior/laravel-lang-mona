<?php

namespace Workbench\App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->setDatabasePath();
        $this->setLangPath();
    }

    public function boot(): void
    {
        Model::preventLazyLoading();
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
