<?php

declare(strict_types=1);

use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\User;

function fakeTestModel(
    ?string $key = null,
    ?string $main = null,
    ?string $fallback = null,
    ?string $custom = null,
): TestModel {
    $key ??= fake()->word();

    $model = TestModel::create(compact('key'));

    if ($main || $fallback || $custom) {
        fakeTranslation($model, $main, $fallback, $custom);
    }

    return $model;
}

function fakeTranslation(TestModel $model, ?string $text, ?string $fallback, ?string $custom): void
{
    $model->setTranslations(FakeValue::Title, [
        FakeLocale::Default  => $text,
        FakeLocale::Fallback => $fallback,
        FakeLocale::Custom   => $custom,
    ])->save();
}

function findFakeTestModel(): TestModel
{
    return TestModel::firstOrFail();
}

function fakeUser(int|string|null $locale = null): User
{
    return User::factory()->createOne([
        'locale' => $locale,
    ]);
}
