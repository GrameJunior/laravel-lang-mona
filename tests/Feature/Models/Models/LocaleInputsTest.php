<?php

declare(strict_types=1);

use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\NativeLocaleNames\LocaleData;
use Workbench\App\Constants\FakeLocale;

test('all forms of a locale refer to the same translation', function (Locale|LocaleData|string $input) {
    $model         = fakeTestModel(main: 'Bonjour', fallback: 'Hallo');
    $translationId = $model->translationsRaw()->where('locale', FakeLocale::Default)->sole()->getKey();

    $model = $model->fresh();

    expect($model->getTranslation('title', $input))->toBe('Bonjour');
    expect($model->hasTranslated('title', $input))->toBeTrue();

    $model->setTranslation('title', 'Salut', $input)->save();

    $model       = $model->fresh();
    $translation = $model->translationsRaw()->where('locale', FakeLocale::Default)->sole();

    expect($translation->getKey())->toBe($translationId)
        ->and($translation->title)->toBe('Salut');
    expect($model->getTranslation('title', FakeLocale::DefaultAlias))->toBe('Salut');

    $model->forgetTranslation($input);

    $model = $model->fresh();

    expect($model->hasTranslated('title', FakeLocale::Default))->toBeFalse();
    expect($model->translationsRaw()->whereKey($translationId)->exists())->toBeFalse();
    expect($model->getTranslation('title', FakeLocale::Fallback))->toBe('Hallo');
})->with([
    'code'        => FakeLocale::Default,
    'alias'       => FakeLocale::DefaultAlias,
    'enum'        => Locale::French,
    'locale data' => fn () => new LocaleData(FakeLocale::Default, FakeLocale::DefaultAlias, 'Français', 'Français'),
]);

test('the translation filter omits a third installed locale while the raw relation keeps it', function (bool $filter) {
    config()->set('localization_models.filter', $filter);

    expect(Locales::isInstalled(FakeLocale::Custom))->toBeTrue();

    $model = fakeTestModel(main: 'Bonjour', fallback: 'Hallo', custom: 'Hello')->fresh();

    expect($model->translations->keys()->sort()->values()->all())
        ->toBe($filter ? ['de', 'fr'] : ['de', 'en', 'fr']);
    expect($model->translationsRaw()->orderBy('locale')->pluck('locale')->all())->toBe(['de', 'en', 'fr']);
    expect($model->getTranslation('title', FakeLocale::Custom))->toBe('Hello');
})->with([true, false]);
