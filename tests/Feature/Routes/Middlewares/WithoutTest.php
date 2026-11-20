<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeTranslation;

use function Pest\Laravel\getJson;

test('without locale passing', function (): void {
    $foo = 'test';

    getJson(route('clean', ['key' => $foo]))
        ->assertSuccessful()
        ->assertJsonPath($foo, FakeTranslation::French);

    assertEventNotDispatched();
});
