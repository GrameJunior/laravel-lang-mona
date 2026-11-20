<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('with guard', function (): void {
    $foo = 'test';

    getJson(route('via.model.guard', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
});

test('without guard', function (): void {
    $foo = 'test';

    getJson(route('via.model.default', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
});
