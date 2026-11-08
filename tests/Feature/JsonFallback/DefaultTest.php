<?php

declare(strict_types=1);
test('php', function () {
    expect(__('custom.failed'))->toBe('French Custom Failed');
    expect(__('custom.password'))->toBe('French Custom Password');
});
test('json', function () {
    expect(__('Remember Me'))->toBe('French Remember Me');
    expect(__('You are logged in!'))->toBe('French You are logged in!');
});
