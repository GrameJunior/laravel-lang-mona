<?php

declare(strict_types=1);

use LaravelLang\Exceptions\AttributeIsNotTranslatableException;
use Workbench\App\Constants\FakeLocale;

test('non-translatable attribute', function () {
    $key = fake()->word;

    $model = fakeTestModel();

    $model->key = $key;
    $model->save();

    expect($model->key)->toBeString()->toBe($key);
});

test('not translatable attribute', function () {
    $model = fakeTestModel();

    $model->setTranslation(
        FakeLocale::Incorrect1,
        FakeLocale::Incorrect1,
        FakeLocale::Uninstalled
    );
})->throws(AttributeIsNotTranslatableException::class);
