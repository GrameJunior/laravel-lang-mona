<?php

declare(strict_types=1);

use LaravelLang\Locales\Facades\Locales;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('soft delete', function () {
    $model = fakeTestModel(main: FakeLocale::Incorrect1);

    assertDatabaseHas(TestModel::class, [
        'id'         => $model->id,
        'deleted_at' => null,
    ]);

    Locales::installed()->each(
        fn (string $locale) => assertDatabaseHas(TestModelTranslation::class, [
            'item_id' => $model->id,
            'locale'  => Locales::fromAlias($locale),
        ])
    );

    $model->delete();

    assertDatabaseHas(TestModel::class, [
        'id'         => $model->id,
        'deleted_at' => now(),
    ]);

    Locales::installed()->each(
        fn (string $locale) => assertDatabaseHas(TestModelTranslation::class, [
            'item_id' => $model->id,
            'locale'  => Locales::fromAlias($locale),
        ])
    );
});

test('force delete', function () {
    $model = fakeTestModel(main: FakeLocale::Incorrect1);

    assertDatabaseHas(TestModel::class, [
        'id'         => $model->id,
        'deleted_at' => null,
    ]);

    Locales::installed()->each(
        fn (string $locale) => assertDatabaseHas(TestModelTranslation::class, [
            'item_id' => $model->id,
            'locale'  => Locales::fromAlias($locale),
        ])
    );

    $model->forceDelete();

    assertDatabaseMissing(TestModel::class, [
        'id' => $model->id,
    ]);

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
    ]);
});

test('restore', function () {
    $model = fakeTestModel(main: FakeLocale::Incorrect1);

    assertDatabaseHas(TestModel::class, [
        'id'         => $model->id,
        'deleted_at' => null,
    ]);

    Locales::installed()->each(
        fn (string $locale) => assertDatabaseHas(TestModelTranslation::class, [
            'item_id' => $model->id,
            'locale'  => Locales::fromAlias($locale),
        ])
    );

    $model->delete();

    assertDatabaseHas(TestModel::class, [
        'id'         => $model->id,
        'deleted_at' => now(),
    ]);

    Locales::installed()->each(
        fn (string $locale) => assertDatabaseHas(TestModelTranslation::class, [
            'item_id' => $model->id,
            'locale'  => Locales::fromAlias($locale),
        ])
    );

    $model->restore();

    assertDatabaseHas(TestModel::class, [
        'id'         => $model->id,
        'deleted_at' => null,
    ]);

    Locales::installed()->each(
        fn (string $locale) => assertDatabaseHas(TestModelTranslation::class, [
            'item_id' => $model->id,
            'locale'  => Locales::fromAlias($locale),
        ])
    );
});
