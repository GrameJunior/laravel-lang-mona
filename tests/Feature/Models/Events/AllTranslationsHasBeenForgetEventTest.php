<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LaravelLang\Models\Events\AllTranslationsHasBeenForgetEvent;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;

beforeEach(function (): void {
    TestModel::create([
        'key' => fake()->word(),

        FakeValue::Title => [
            FakeLocale::Default  => 'qwerty 10',
            FakeLocale::Fallback => 'qwerty 11',
        ],

        FakeValue::Description => [
            FakeLocale::Default  => 'qwerty 20',
            FakeLocale::Fallback => 'qwerty 21',
        ],
    ]);

    Event::fake(AllTranslationsHasBeenForgetEvent::class);
});

test('all', function (): void {
    $model = findFakeTestModel();

    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Default))->toBeTrue();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Fallback))->toBeTrue();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe('qwerty 10');
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe('qwerty 11');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Default))->toBe('qwerty 20');
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Fallback))->toBe('qwerty 21');

    $model->forgetAllTranslations();

    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Title, FakeLocale::Fallback))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Default))->toBeFalse();
    expect($model->hasTranslated(FakeValue::Description, FakeLocale::Fallback))->toBeFalse();

    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Description, FakeLocale::Fallback))->toBeNull();

    Event::assertDispatched(AllTranslationsHasBeenForgetEvent::class);
});
