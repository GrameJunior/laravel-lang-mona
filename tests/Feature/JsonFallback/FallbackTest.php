<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;

beforeEach(function () {
    config()->set('app.locale', FakeLocale::Incorrect1);
});
test('php', function () {
    expect(__('custom.failed'))->toMatchSnapshot();
    expect(__('custom.password'))->toMatchSnapshot();
});
test('json', function () {
    expect(__('Remember Me'))->toMatchSnapshot();
    expect(__('You are logged in!'))->toMatchSnapshot();
});
