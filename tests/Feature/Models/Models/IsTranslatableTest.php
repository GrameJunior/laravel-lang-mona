<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;

test('attributes', function () {
    $model = fakeTestModel();

    expect($model->isTranslatable(FakeValue::Title))->toBeTrue();
    expect($model->isTranslatable(FakeValue::Description))->toBeTrue();

    expect($model->isTranslatable('key'))->toBeFalse();
    expect($model->isTranslatable(FakeLocale::Incorrect1))->toBeFalse();
});
