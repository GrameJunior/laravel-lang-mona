<?php

declare(strict_types=1);
beforeEach(function () {
    config()->set('app.locale', 'foo');
});
test('php', function () {
    expect(__('custom.failed'))->toBe('German Custom Failed');
    expect(__('custom.password'))->toBe('German Custom Password');
});
test('json', function () {
    expect(__('Remember Me'))->toBe('German Remember Me');
    expect(__('You are logged in!'))->toBe('German You are logged in!');
});
