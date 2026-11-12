<?php

declare(strict_types=1);

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Models\Exceptions\AttributeIsNotTranslatableException;
use LaravelLang\Models\Exceptions\UnavailableLocaleException;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;

test('main locale', function () {
    $text = fake()->paragraph;

    $model = fakeTestModel(main: $text);

    expect($model->title)->toBeString()->toBe($text);
    expect($model->description)->toBeNull();

    expect($model->getTranslation(FakeValue::Title))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
});

test('fallback locale', function () {
    $text = fake()->paragraph;

    $model = fakeTestModel(fallback: $text);

    expect($model->title)->toBeString()->toBe($text);

    expect($model->getTranslation(FakeValue::Title))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
});

test('custom locale', function () {
    $text = fake()->paragraph;

    $model = fakeTestModel(custom: $text);

    expect($model->title)->toBeNull();

    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe($text);
});

test('aliased locale', function () {
    config([
        'app.locale' => 'zh_CN',

        'localization_main.aliases' => [
            Locale::Chinese->value => 'zh',
        ],
    ]);

    app(Filesystem::class)->ensureDirectoryExists(
        lang_path('zh')
    );

    $text = fake()->paragraph;

    $model = fakeTestModel();

    $model->setTranslation(FakeValue::Title, $text, 'zh');
    $model->save();

    expect($model->title)->toBeString()->toBe($text);
    expect($model->getTranslation(FakeValue::Title, Locale::Chinese->value))->toBeString()->toBe($text);
    expect($model->getTranslation(FakeValue::Title, 'zh'))->toBeString()->toBe($text);
    expect($model->getTranslation(FakeValue::Title))->toBeString()->toBe($text);

    assertDatabaseHas(TestModelTranslation::class, [
        'item_id' => $model->id,
        'locale'  => Locale::Chinese->value,
        'title'   => $text,
    ]);
});

test('uninstalled', function () {
    $model = fakeTestModel();

    $model->getTranslation(FakeValue::Title, FakeLocale::Uninstalled);
})->throws(UnavailableLocaleException::class);

test('without translations model', function () {
    $model = fakeTestModel();

    assertDatabaseEmpty(TestModelTranslation::class);

    expect($model->title)->toBeNull();

    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
});

test('lazy loading', function (bool $enabled, int $count, array $locales) {
    config()->set('localization_models.filter', $enabled);

    $hasNonFilteredQuery = false;
    $hasFilteredQuery    = false;

    DB::listen(function (QueryExecuted $query) use (&$hasNonFilteredQuery, &$hasFilteredQuery) {
        if (Str::is('select * where *."item_id" = ? and *."item_id" is not null', $query->sql)) {
            $hasNonFilteredQuery = true;
        }

        if (Str::is('select * where *."item_id" = ? and *."item_id" is not null and "locale" in (?, ?)', $query->sql)) {
            $hasFilteredQuery = true;
        }
    });

    $model1 = fakeTestModel(main: FakeLocale::Incorrect1);
    $model2 = fakeTestModel(main: 'Bar');

    $model1->load('translations');
    $model2->load('translations');

    expect($model1->relationLoaded('translations'))->toBeTrue();
    expect($model2->relationLoaded('translations'))->toBeTrue();

    expect($model1->translations()->count())->toBe($count);
    expect($model2->translations()->count())->toBe($count);

    expect($model1->translations->count())->toBe($count);
    expect($model2->translations->count())->toBe($count);

    expect($model1->translations->pluck('locale')->sort()->values()->all())->toBe($locales);
    expect($model2->translations->pluck('locale')->sort()->values()->all())->toBe($locales);

    expect($hasNonFilteredQuery)->toBe(! $enabled);
    expect($hasFilteredQuery)->toBe($enabled);
})->with('locales filter');

test('non-translatable attribute', function () {
    $key = fake()->word;

    $model = fakeTestModel($key);

    expect($model->key)->toBeString()->toBe($key);
});

test('not translatable attribute', function () {
    $model = fakeTestModel();

    $model->getTranslation(FakeLocale::Incorrect1);
})->throws(AttributeIsNotTranslatableException::class);

test('translated scope returns records with at least one translation', function () {
    $text = fake()->paragraph;

    TestModel::create(['key' => FakeLocale::Fallback]);
    TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => $text,
        ],
    ]);

    expect(TestModel::translated()->count())->toBe(1);
    expect(TestModel::translated()->first()->{FakeValue::Title})->toBe($text);
});

test('whereTranslation filters by translation', function () {
    $text = 'Hello world';

    $model = TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => $text,
        ],
    ]);

    expect(TestModel::whereTranslation(FakeValue::Title, 'Hello')->count())->toBeEmpty();
    expect(TestModel::whereTranslation(FakeValue::Title, 'Hello world')->first()->id)->toBe($model->id);
});

test('or whereTranslation filters by translation', function () {
    $model1 = TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => 'Texte en français',
        ],
    ]);
    $model2 = TestModel::create([
        'key'            => FakeLocale::Fallback,
        FakeValue::Title => [
            FakeLocale::Fallback => 'Text auf Deutsch',
        ],
    ]);

    $result = TestModel::query()
        ->whereTranslation(FakeValue::Title, 'Texte en français')
        ->orWhereTranslation(FakeValue::Title, 'Text auf Deutsch')
        ->get();

    expect($result->count())->toBe(2);
    expect($result->first()->id)->toBe($model1->id);
    expect($result->last()->id)->toBe($model2->id);
});

test('where translation filters by translation and locale', function () {
    $text = 'Hello world';

    $model1 = TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => $text,
        ],
    ]);
    TestModel::create([
        'key'            => FakeLocale::Fallback,
        FakeValue::Title => [
            FakeLocale::Fallback => $text,
        ],
    ]);

    expect(TestModel::whereTranslation(FakeValue::Title, $text)->count())->toBe(2);

    $result = TestModel::query()
        ->whereTranslation(FakeValue::Title, $text, FakeLocale::Default)
        ->get();

    expect($result->count())->toBe(1);
    expect($result->first()->id)->toBe($model1->id);
});

test('whereTranslationLike filters by translation', function () {
    $text = 'Hello world';

    TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => $text,
        ],
    ]);

    expect(TestModel::whereTranslationLike(FakeValue::Title, 'wor')->count())->toBe(0);
    expect(TestModel::whereTranslationLike(FakeValue::Title, '%wor%')->count())->toBe(1);
});

test('or whereTranslationLike filters by translation', function () {
    $model1 = TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => 'Texte en français',
        ],
    ]);
    $model2 = TestModel::create([
        'key'            => FakeLocale::Fallback,
        FakeValue::Title => [
            FakeLocale::Fallback => 'Text auf Deutsch',
        ],
    ]);

    $result = TestModel::query()
        ->whereTranslationLike(FakeValue::Title, '%français')
        ->orWhereTranslationLike(FakeValue::Title, '%Deutsch')
        ->get();

    expect($result->count())->toBe(2);
    expect($result->first()->id)->toBe($model1->id);
    expect($result->last()->id)->toBe($model2->id);
});

test('whereTranslationLike filters by translation and locale', function () {
    $text = 'Hello world';

    $model1 = TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default => $text,
        ],
    ]);
    TestModel::create([
        'key'            => FakeLocale::Fallback,
        FakeValue::Title => [
            FakeLocale::Fallback => $text,
        ],
    ]);

    expect(TestModel::query()->whereTranslationLike(FakeValue::Title, '%world%')->count())->toBe(2);

    $result = TestModel::query()->whereTranslationLike(FakeValue::Title, '%world%', FakeLocale::Default)->get();

    expect($result->count())->toBe(1);
    expect($result->first()->id)->toBe($model1->id);
});

test(
    'orderByTranslation sorts by key',
    function (string $sortMethod, ?string $sortLocale, string $firstLocale, string $secondLocale) {
        TestModel::create([
            'key' => FakeLocale::Default,

            FakeValue::Title => [
                FakeLocale::Default  => 'A',
                FakeLocale::Fallback => 'D',
            ],
        ]);

        TestModel::create([
            'key' => FakeLocale::Fallback,

            FakeValue::Title => [
                FakeLocale::Default  => 'B',
                FakeLocale::Fallback => 'C',
            ],
        ]);

        expect(TestModel::query()->orderByTranslation(FakeValue::Title, $sortMethod, $sortLocale)->get()->first()->key)
            ->toBe($firstLocale);

        expect(
            TestModel::query()
                ->orderByTranslation(FakeValue::Title, 'asc', $secondLocale)
                ->get()
                ->first()->key
        )->toBe($secondLocale);
    }
)->with([
    'asc without locale' => ['asc', null, FakeLocale::Default, FakeLocale::Fallback],
    'asc with locale'    => ['asc', FakeLocale::Default, FakeLocale::Default, FakeLocale::Fallback],

    'desc' => ['desc', null, FakeLocale::Fallback, FakeLocale::Fallback],
]);

test('orderByTranslation sorts by key asc even if locale is missing', function () {
    TestModel::create([
        'key'            => FakeLocale::Default,
        FakeValue::Title => [
            FakeLocale::Default  => 'Pommes de Terre',
            FakeLocale::Fallback => 'Kartoffeln',
        ],
    ]);
    TestModel::create([
        'key'            => FakeLocale::Fallback,
        FakeValue::Title => [
            FakeLocale::Default  => 'Fraises',
            FakeLocale::Fallback => 'Erdbeeren',
        ],
    ]);
    TestModel::create([
        'key' => FakeLocale::Custom,
    ]);

    $orderInFrench = TestModel::orderByTranslation(FakeValue::Title)->get();
    expect($orderInFrench->pluck(FakeValue::Title)->toArray())->toBe([null, 'Fraises', 'Pommes de Terre']);

    app()->setLocale(FakeLocale::Fallback);
    $orderInDeutsch = TestModel::orderByTranslation(FakeValue::Title, 'desc')->get();
    expect($orderInDeutsch->pluck(FakeValue::Title)->toArray())->toBe(['Kartoffeln', 'Erdbeeren', null]);
});
