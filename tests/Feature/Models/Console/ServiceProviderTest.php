<?php

declare(strict_types=1);

namespace Tests\Feature\Models\Console;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Artisan;
use LaravelLang\Models\LangModelsServiceProvider;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function createApplication()
    {
        $env      = Env::getRepository();
        $key      = 'APP_RUNNING_IN_CONSOLE';
        $previous = $env->get($key);
        $env->set($key, 'false');

        try {
            return parent::createApplication();
        } finally {
            if ($previous === null) {
                $env->clear($key);
            } else {
                $env->set($key, $previous);
            }
        }
    }

    public function testTheProviderDoesNotRegisterArtisanCommandsOutsideTheConsole(): void
    {
        $this->assertFalse($this->app->runningInConsole());
        $this->assertNotNull($this->app->getProvider(LangModelsServiceProvider::class));

        $commands = Artisan::all();

        $this->assertArrayNotHasKey('lang:models-helper', $commands);
        $this->assertArrayNotHasKey('make:model-translation', $commands);
    }
}
