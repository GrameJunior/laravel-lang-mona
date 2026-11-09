<?php

use Illuminate\Support\Facades\Event;
use Tests\TestCase;

use function Pest\Laravel\withHeader;

pest()
    ->printer()
    ->compact();

pest()
    ->extend(TestCase::class)
    ->in('Feature')
    ->beforeEach(function () {
        cleanUpLang();
    });

pest()
    ->in('Feature/Routes')
    ->beforeEach(function () {
        Event::fake();

        withHeader('Accept-Language', 'zxc');
    });
