<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Event;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\TestCase;

use function Pest\Laravel\withHeader;

pest()
    ->printer()
    ->compact();

pest()
    ->extend(BaseTestCase::class)
    ->in('Architecture');

pest()
    ->extend(TestCase::class)
    ->in('Feature')
    ->group('feature')
    ->beforeEach(function (): void {
        Date::setTestNow('2026-11-20T22:56:24Z');

        cleanUpLang();
        fakePluginCollector();
    });

pest()
    ->in('Feature/Routes')
    ->beforeEach(function (): void {
        Event::fake();

        withHeader('Accept-Language', 'qwerty');
    });
