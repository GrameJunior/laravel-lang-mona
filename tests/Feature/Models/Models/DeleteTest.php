<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaravelLang\Locales\Facades\Locales;
use Tests\Fixtures\Models\SoftDeletingTestModel;
use Tests\Fixtures\Models\SoftDeletingTestModelTranslation;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('soft delete', function (): void {
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

test('force delete', function (): void {
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

test('restore', function (): void {
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

test('forgetting a translation respects soft deletion on its translation model', function (): void {
    Schema::table('test_model_translations', fn (Blueprint $table) => $table->softDeletes());

    $model = SoftDeletingTestModel::create([
        'key'   => 'article',
        'title' => [FakeLocale::Default => 'Bonjour', FakeLocale::Fallback => 'Hallo'],
    ]);
    $translation = $model->translationsRaw()->where('locale', FakeLocale::Default)->sole();

    $model->forgetTranslation(FakeLocale::DefaultAlias);

    $model = $model->fresh();

    expect($model->getTranslation('title', FakeLocale::Default))->toBeNull();
    expect($model->getTranslation('title', FakeLocale::Fallback))->toBe('Hallo');
    expect($model->translationsRaw()->pluck('locale')->all())->toBe([FakeLocale::Fallback]);
    expect(SoftDeletingTestModelTranslation::withTrashed()->findOrFail($translation->getKey())->trashed())->toBeTrue();
});
