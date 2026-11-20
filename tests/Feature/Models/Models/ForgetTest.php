<?php

declare(strict_types=1);

use LaravelLang\Exceptions\UnknownLocaleException;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

beforeEach(
    fn () => TestModel::create([
        'key' => fake()->word(),

        FakeValue::Title => [
            FakeLocale::Default  => 'qwerty 10',
            FakeLocale::Fallback => 'qwerty 11',
            FakeLocale::Custom   => 'qwerty 12',
        ],

        FakeValue::Description => [
            FakeLocale::Default  => 'qwerty 20',
            FakeLocale::Fallback => 'qwerty 21',
            FakeLocale::Custom   => 'qwerty 22',
        ],
    ])
);

test('locale', function (): void {
    $model = findFakeTestModel();

    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Fallback))->toBeTrue();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe('qwerty 10');
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe('qwerty 11');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Default))->toBe('qwerty 20');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Fallback))->toBe('qwerty 21');

    $model->forgetTranslation(FakeLocale::Default);

    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Fallback))->toBeTrue();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe('qwerty 11');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Fallback))->toBe('qwerty 21');

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Fallback,
    ]);
});

test('all', function (): void {
    $model = findFakeTestModel();

    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Custom))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Fallback))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Custom))->toBeTrue();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe('qwerty 10');
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe('qwerty 11');
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe('qwerty 12');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Default))->toBe('qwerty 20');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Fallback))->toBe('qwerty 21');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Custom))->toBe('qwerty 22');

    $model->forgetAllTranslations();

    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Custom))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Fallback))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Custom))->toBeFalse();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Custom))->toBeNull();

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
    ]);
});

test('non-translatable locale', function (): void {
    $model = fakeTestModel();

    $model->forgetTranslation(FakeLocale::Incorrect1);
})->throws(UnknownLocaleException::class);
