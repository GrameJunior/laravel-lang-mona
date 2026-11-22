<?php

declare(strict_types=1);

use LaravelLang\Exceptions\InvalidTranslationModelException;
use LaravelLang\Models\Eloquent\Translation;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;

test('attributes', function (): void {
    $model = fakeTestModel();

    expect($model->isTranslatable(FakeValue::Title))->toBeTrue();
    expect($model->isTranslatable(FakeValue::Description))->toBeTrue();

    expect($model->isTranslatable('key'))->toBeFalse();
    expect($model->isTranslatable(FakeLocale::Incorrect1))->toBeFalse();
});

test('reports a missing translation model', function (): void {
    $model = fakeTestModel();

    config()->set('localization_models.suffix', 'MissingTranslation');

    $model->getTranslation(FakeValue::Title);
})->throws(
    InvalidTranslationModelException::class,
    "Translation model '" . TestModel::class . "MissingTranslation' must extend '" . Translation::class . "'."
);
