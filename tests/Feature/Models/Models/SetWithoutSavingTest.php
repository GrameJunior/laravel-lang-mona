<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

test('main locale', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeTestModel(main: $oldText);

    expect($model->title)->toBeString()->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title))->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($oldText);

    // Change that
    $model->setTranslation(FakeValue::Title, $newText);

    expect($model->title)->toBeString()->toBe($newText);
    expect($model->getTranslation(FakeValue::Title))->toBe($newText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($newText);

    // Check database
    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $oldText,
    ]);

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Default,

        FakeValue::Title => $newText,
    ]);
});

test('fallback locale', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeTestModel(fallback: $oldText);

    expect($model->title)->toBeString()->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title))->toBe($oldText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe($oldText);

    // Change that
    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Fallback);

    expect($model->title)->toBeString()->toBe($newText);
    expect($model->getTranslation(FakeValue::Title))->toBe($newText);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe($newText);

    // Check database
    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Fallback,

        FakeValue::Title => $oldText,
    ]);

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Fallback,

        FakeValue::Title => $newText,
    ]);
});

test('custom locale', function () {
    $oldText = fake()->paragraph;
    $newText = fake()->paragraph;

    $model = fakeTestModel(custom: $oldText);

    expect($model->title)->toBeNull();
    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe($oldText);

    // Change that
    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Custom);

    expect($model->title)->toBeNull();
    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe($newText);

    // Check database
    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Custom,

        FakeValue::Title => $oldText,
    ]);

    assertDatabaseMissing(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => FakeLocale::Custom,

        FakeValue::Title => $newText,
    ]);
});

test('mixed symbols', function (mixed $source, mixed $saved) {
    $model = fakeTestModel();

    $model->setTranslation(FakeValue::Title, $source, FakeLocale::Default);

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($saved);

    assertDatabaseEmpty(TestModelTranslation::class);
})->with('mixed values');
