<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use LaravelLang\Models\Events\TranslationHasBeenSetEvent;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;

beforeEach(
    fn () => Event::fake(TranslationHasBeenSetEvent::class)
);

test('default locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(main: $oldText);

    $model->setTranslation(FakeValue::Title, $newText);

    Event::assertDispatched(
        fn (TranslationHasBeenSetEvent $event): bool => $event->model->getKey() === $model->getKey()
            && $event->column                                                   === FakeValue::Title
            && $event->locale                                                   === null
            && $event->oldValue                                                 === $oldText
            && $event->newValue                                                 === $newText
    );
});

test('main locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(main: $oldText);

    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Default);

    Event::assertDispatched(
        fn (TranslationHasBeenSetEvent $event): bool => $event->model->getKey() === $model->getKey()
            && $event->column                                                   === FakeValue::Title
            && $event->locale                                                   === FakeLocale::DefaultAlias
            && $event->oldValue                                                 === $oldText
            && $event->newValue                                                 === $newText
    );
});

test('fallback locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(fallback: $oldText);

    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Fallback);

    Event::assertDispatched(
        fn (TranslationHasBeenSetEvent $event): bool => $event->model->getKey() === $model->getKey()
            && $event->column                                                   === FakeValue::Title
            && $event->locale                                                   === FakeLocale::Fallback
            && $event->oldValue                                                 === $oldText
            && $event->newValue                                                 === $newText
    );
});

test('custom locale', function (): void {
    $oldText = fake()->paragraph();
    $newText = fake()->paragraph();

    $model = fakeTestModel(custom: $oldText);

    $model->setTranslation(FakeValue::Title, $newText, FakeLocale::Custom);

    Event::assertDispatched(
        fn (TranslationHasBeenSetEvent $event): bool => $event->model->getKey() === $model->getKey()
            && $event->column                                                   === FakeValue::Title
            && $event->locale                                                   === FakeLocale::English
            && $event->oldValue                                                 === $oldText
            && $event->newValue                                                 === $newText
    );
});
