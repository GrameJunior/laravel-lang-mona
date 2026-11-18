<?php

declare(strict_types=1);

use LaravelLang\Exceptions\UnknownLocaleException;
use LaravelLang\LocaleList\Locale;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('single', function () {
    assertDatabaseEmpty(TestModel::class);
    assertDatabaseEmpty(TestModelTranslation::class);

    $model = TestModel::create([
        'key' => FakeLocale::Incorrect1,

        FakeValue::Title       => 'qwerty 10',
        FakeValue::Description => 'qwerty 20',
    ]);

    expect($model->key)->toBeString()->toBe(FakeLocale::Incorrect1);
    expect($model->title)->toBeString()->toBe('qwerty 10');
    expect($model->description)->toBeString()->toBe('qwerty 20');

    assertDatabaseHas(TestModel::class, [
        'id'  => $model->id,
        'key' => $model->key,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => Locale::French,

        FakeValue::Title       => 'qwerty 10',
        FakeValue::Description => 'qwerty 20',
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => Locale::German,

        FakeValue::Title       => null,
        FakeValue::Description => null,
    ]);

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => Locale::English,
    ]);
});

test('array', function () {
    assertDatabaseEmpty(TestModel::class);
    assertDatabaseEmpty(TestModelTranslation::class);

    $model = TestModel::create([
        'key' => FakeLocale::Incorrect1,

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
    ]);

    expect($model->key)->toBeString()->toBe(FakeLocale::Incorrect1);
    expect($model->title)->toBeString()->toBe('qwerty 10');
    expect($model->description)->toBeString()->toBe('qwerty 20');

    assertDatabaseHas(TestModel::class, [
        'id'  => $model->id,
        'key' => $model->key,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => FakeLocale::Default,

        FakeValue::Title       => 'qwerty 10',
        FakeValue::Description => 'qwerty 20',
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => FakeLocale::Fallback,

        FakeValue::Title       => 'qwerty 11',
        FakeValue::Description => 'qwerty 21',
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => FakeLocale::Custom,

        FakeValue::Title       => 'qwerty 12',
        FakeValue::Description => 'qwerty 22',
    ]);
});

test('collection', function () {
    assertDatabaseEmpty(TestModel::class);
    assertDatabaseEmpty(TestModelTranslation::class);

    $model = TestModel::create([
        'key' => FakeLocale::Incorrect1,

        FakeValue::Title => collect([
            FakeLocale::Default  => 'qwerty 10',
            FakeLocale::Fallback => 'qwerty 11',
            FakeLocale::Custom   => 'qwerty 12',
        ]),

        FakeValue::Description => collect([
            FakeLocale::Default  => 'qwerty 20',
            FakeLocale::Fallback => 'qwerty 21',
            FakeLocale::Custom   => 'qwerty 22',
        ]),
    ]);

    expect($model->key)->toBeString()->toBe(FakeLocale::Incorrect1);
    expect($model->title)->toBeString()->toBe('qwerty 10');
    expect($model->description)->toBeString()->toBe('qwerty 20');

    assertDatabaseHas(TestModel::class, [
        'id'  => $model->id,
        'key' => $model->key,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => FakeLocale::Default,

        FakeValue::Title       => 'qwerty 10',
        FakeValue::Description => 'qwerty 20',
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => FakeLocale::Fallback,

        FakeValue::Title       => 'qwerty 11',
        FakeValue::Description => 'qwerty 21',
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,

        'locale' => FakeLocale::Custom,

        FakeValue::Title       => 'qwerty 12',
        FakeValue::Description => 'qwerty 22',
    ]);
});

test('uninstalled', function () {
    TestModel::create([
        'key' => FakeLocale::Incorrect1,

        FakeValue::Title => [
            FakeLocale::Default     => 'qwerty 10',
            FakeLocale::Uninstalled => 'qwerty 11',
        ],

        FakeValue::Description => [
            FakeLocale::Default     => 'qwerty 20',
            FakeLocale::Uninstalled => 'qwerty 21',
        ],
    ]);
})->throws(UnknownLocaleException::class);

test('unknown', function () {
    TestModel::create([
        'key' => FakeLocale::Incorrect1,

        FakeValue::Title => [
            'qwerty' => 'qwerty 10',
        ],

        FakeValue::Description => [
            'qwerty' => 'qwerty 20',
        ],
    ]);
})->throws(UnknownLocaleException::class);
