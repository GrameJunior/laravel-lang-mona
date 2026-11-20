<?php

declare(strict_types=1);
test('php', function (): void {
    expect(__('custom.failed'))->toMatchSnapshot();
    expect(__('custom.password'))->toMatchSnapshot();
});
test('json', function (): void {
    expect(__('Remember Me'))->toMatchSnapshot();
    expect(__('You are logged in!'))->toMatchSnapshot();
});
