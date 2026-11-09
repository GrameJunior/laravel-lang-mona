<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Models\Events\TranslationHasBeenForgetEvent;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;

beforeEach(fn () => TestModel::create([
    'key' => fake()->word,

    FakeValue::Title => [
        FakeLocale::Default  => 'qwerty 10',
        FakeLocale::Fallback => 'qwerty 11',
    ],

    FakeValue::Description => [
        FakeLocale::Default  => 'qwerty 20',
        FakeLocale::Fallback => 'qwerty 21',
    ],
]));

test('forget locale', function () {
    Event::fake(TranslationHasBeenForgetEvent::class);

    $model = findFakeTestModel();

    $model->forgetTranslation(FakeLocale::Default);

    Event::assertDispatched(function (TranslationHasBeenForgetEvent $event) use ($model) {
        return $event->model->getKey() === $model->getKey()
            && $event->locale === Locale::French;
    });
});
