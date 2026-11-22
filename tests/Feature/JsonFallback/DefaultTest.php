<?php

declare(strict_types=1);

use LaravelLang\Exceptions\InvalidTranslationLoaderException;

test('php', function (): void {
    expect(__('custom.failed'))->toMatchSnapshot();
    expect(__('custom.password'))->toMatchSnapshot();
});
test('json', function (): void {
    expect(__('Remember Me'))->toMatchSnapshot();
    expect(__('You are logged in!'))->toMatchSnapshot();
});

test('reports an incompatible translation loader when resolving translations', function (): void {
    app('translator');
    app()->instance('translation.loader', new stdClass);
    app()->forgetInstance('translator');

    expect(fn (): array|string|null => __('Remember Me'))
        ->toThrow(InvalidTranslationLoaderException::class);
});
