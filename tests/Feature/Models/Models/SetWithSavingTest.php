<?php

declare(strict_types=1);

use LaravelLang\Exceptions\InvalidTranslationsRelationException;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('main locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(main: $oldText);

    expect($model->{FakeValue::Title})->toBeString()->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title))->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($oldText);

    // That one
    $model->setTranslation(FakeValue::Title, $newText);
    $model->save();

    expect($model->{FakeValue::Title})->toBeString()->toBe($newText);
    expect($model->getTranslation(FakeValue::Title))->toBe($newText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($newText);

    // Check database
    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $oldText,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $newText,
    ]);
});

test('assigning a translatable property persists the value for the current locale', function (): void {
    $model        = fakeTestModel(main: 'Old title');
    $model->title = 'Updated title';
    $model->save();

    expect($model->fresh()->getTranslation('title', FakeLocale::Default))->toBe('Updated title');
});

test('rejects assigning a non-collection translations relation', function (): void {
    $model = new TestModel;

    expect(fn (): TestModel => $model->setRelation('translations', []))
        ->toThrow(InvalidTranslationsRelationException::class, 'The translations relation must be a collection.');
});

test('rejects an invalid translations relation when saving a model', function (mixed $translations, string $message): void {
    $model = fakeTestModel();
    $model->setRelations(['translations' => $translations]);

    expect($model->save(...))->toThrow(InvalidTranslationsRelationException::class, $message);
})->with([
    'non-collection relation' => [[], 'The translations relation must be a collection.'],
    'invalid related model'   => [collect(['invalid']), 'The translations relation must contain only translation models.'],
]);

test('fallback locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(fallback: $oldText);

    expect($model->{FakeValue::Title})->toBeString()->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title))->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe($oldText);

    // That one
    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Fallback);
    $model->save();

    expect($model->{FakeValue::Title})->toBeString()->toBe($newText);
    expect($model->getTranslation(FakeValue::Title))->toBe($newText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe($newText);

    // Check database
    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Fallback,

        FakeValue::Title => $oldText,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Fallback,

        FakeValue::Title => $newText,
    ]);
});

test('custom locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(custom: $oldText);

    expect($model->{FakeValue::Title})->toBeNull();
    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe($oldText);

    // That one
    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Custom);
    $model->save();

    expect($model->{FakeValue::Title})->toBeNull();
    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe($newText);

    // Check database
    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Custom,

        FakeValue::Title => $oldText,
    ]);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Custom,

        FakeValue::Title => $newText,
    ]);
});

test('mixed symbols', function (mixed $source, mixed $saved): void {
    $model = fakeTestModel();

    $model->setTranslation(FakeValue::Title, $source, FakeLocale::Default);
    $model->save();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($saved);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $saved,
    ]);
})->with('mixed values');

test('array', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(main: $oldText);

    expect($model->{FakeValue::Title})->toBeString()->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title))->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($oldText);

    // That one
    $model->update([
        FakeValue::Title => [
            FakeLocale::Default => $newText,
        ],
    ]);

    expect($model->{FakeValue::Title})->toBeString()->toBe($newText);
    expect($model->getTranslation(FakeValue::Title))->toBe($newText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($newText);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $newText,
    ]);
});
