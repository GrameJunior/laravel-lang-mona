<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;

use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('main locale', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeModel(main: $oldText);

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

test('fallback locale', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeModel(fallback: $oldText);

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

test('custom locale', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeModel(custom: $oldText);

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

test('mixed symbols', function (mixed $source, mixed $saved) {
    $model = fakeModel();

    $model->setTranslation(FakeValue::Title, $source, FakeLocale::Default);
    $model->save();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($saved);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $saved,
    ]);
})->with('mixed values');

test('array', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeModel(main: $oldText);

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
