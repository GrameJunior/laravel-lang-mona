<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;

test('main', function () {
    $model = fakeModel(
        main: fake()->paragraph
    );

    expect($model->hasTranslated(FakeValue::Title))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeFalse();
});

test('fallback', function () {
    $model = fakeModel(
        fallback: fake()->paragraph
    );

    expect($model->hasTranslated(FakeValue::Title))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeTrue();
});

test('custom', function () {
    $model = fakeModel(
        custom: fake()->paragraph
    );

    expect($model->hasTranslated(FakeValue::Title))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Custom))->toBeTrue();
});
