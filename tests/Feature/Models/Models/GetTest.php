<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LaravelLang\Exceptions\AttributeIsNotTranslatableException;
use LaravelLang\Exceptions\InvalidStoredAttributeValueException;
use LaravelLang\Exceptions\UnknownLocaleException;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Models\Casts\TrimStringCast;
use LaravelLang\Models\Eloquent\Translation;
use Workbench\App\Constants\FakeLocale;
use Workbench\App\Constants\FakeValue;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\TestModelTranslation;

use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;

test('main locale', function (): void {
    $text = fake()->paragraph();

    $model = fakeTestModel(main: $text);

    expect($model->title)->toBeString()->toBe($text);
    expect($model->description)->toBeNull();

    expect($model->getTranslation(FakeValue::Title))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
});

test('fallback locale', function (): void {
    $text = fake()->paragraph();

    $model = fakeTestModel(fallback: $text);

    expect($model->title)->toBeString()->toBe($text);

    expect($model->getTranslation(FakeValue::Title))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBe($text);
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
});

test('custom locale', function (): void {
    $text = fake()->paragraph();

    $model = fakeTestModel(custom: $text);

    expect($model->title)->toBeNull();

    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBe($text);
});

test('aliased locale', function (): void {
    config([
        'app.locale' => 'zh_CN',

        'localization_main.aliases' => [
            Locale::Chinese->value => 'zh',
        ],
    ]);

    resolve(Filesystem::class)->ensureDirectoryExists(
        lang_path('zh')
    );

    $text = fake()->paragraph();

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

test('uninstalled', function (): void {
    $model = fakeTestModel();

    $model->getTranslation(FakeValue::Title, FakeLocale::Uninstalled);
})->throws(UnknownLocaleException::class);

test('without translations model', function (): void {
    $model = fakeTestModel();

    assertDatabaseEmpty(TestModelTranslation::class);

    expect($model->title)->toBeNull();

    expect($model->getTranslation(FakeValue::Title))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Default))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Fallback))->toBeNull();
    expect($model->getTranslation(FakeValue::Title, FakeLocale::Custom))->toBeNull();
});

test('loads raw translations as an ordinary Eloquent relation', function (): void {
    $model        = fakeTestModel();
    $translations = [
        FakeLocale::Default  => 'French title',
        FakeLocale::Fallback => 'German title',
    ];

    $model->setTranslations('title', $translations)->save();
    $model->load('translationsRaw');

    expect($model->translationsRaw->keys()->all())->toBe([0, 1])
        ->and($model->translationsRaw->pluck('title', 'locale')->all())->toMatchArray($translations);
});

test('eager loads translations when the model explicitly lists the relation', function (): void {
    $saved = fakeTestModel(main: 'Bonjour');

    $model = new class extends TestModel {
        protected $table = 'test_models';

        protected $with = ['translations'];

        public function translationModel(): string
        {
            return TestModelTranslation::class;
        }
    };

    $loaded = $model->newQuery()->findOrFail($saved->getKey());

    expect($loaded->relationLoaded('translations'))->toBeTrue()
        ->and($loaded->getTranslation(FakeValue::Title))->toBe('Bonjour');
});

test('supports translation casts declared as model properties', function (): void {
    $translation = new class extends Translation {
        public $casts = ['title' => TrimStringCast::class];
    };

    $translation->title = ' Bonjour ';

    expect($translation->title)->toBe('Bonjour');
});

test('rejects a non-scalar stored translation value on read', function (): void {
    $translation = (new TestModelTranslation)->newFromBuilder(['title' => ['Bonjour']]);

    expect(fn () => $translation->title)
        ->toThrow(InvalidStoredAttributeValueException::class, 'The stored value must be a scalar or null.');
});

test('preserves a model constraint on default eager loaded translations', function (): void {
    $saved = fakeTestModel(main: 'Bonjour', fallback: 'Hallo');

    $model = new class extends TestModel {
        protected $table = 'test_models';

        public function __construct(array $attributes = [])
        {
            $this->with = [
                'translations' => static fn (HasMany $relation) => $relation->where('locale', FakeLocale::Fallback),
            ];

            parent::__construct($attributes);
        }

        public function translationModel(): string
        {
            return TestModelTranslation::class;
        }
    };

    $loaded = $model->newQuery()->findOrFail($saved->getKey());

    expect($loaded->relationLoaded('translations'))->toBeTrue()
        ->and($loaded->translations->keys()->all())->toBe([FakeLocale::Fallback]);
});

test('lazy loading', function (bool $enabled, int $count, array $locales): void {
    config()->set('localization_models.filter', $enabled);

    $hasNonFilteredQuery = false;
    $hasFilteredQuery    = false;

    DB::listen(function (QueryExecuted $query) use (&$hasNonFilteredQuery, &$hasFilteredQuery): void {
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

test('non-translatable attribute', function (): void {
    $key = fake()->word();

    $model = fakeTestModel($key);

    expect($model->key)->toBeString()->toBe($key);
});

test('not translatable attribute', function (): void {
    $model = fakeTestModel();

    $model->getTranslation(FakeLocale::Incorrect1);
})->throws(AttributeIsNotTranslatableException::class);

test('translated scope returns records with at least one translation', function (): void {
    $text = fake()->paragraph();

    TestModel::create(['key' => FakeLocale::Fallback]);

    TestModel::create([
        'key' => FakeLocale::Default,

        FakeValue::Title => [
            FakeLocale::Default => $text,
        ],
    ]);

    expect(TestModel::translated()->count())->toBe(1);
    expect(TestModel::translated()->first()->getAttribute(FakeValue::Title))->toBe($text);
});

test('whereTranslation filters by translation', function (): void {
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

test('or whereTranslation filters by translation', function (): void {
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

test('where translation filters by translation and locale', function (): void {
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

test('whereTranslationLike filters by translation', function (): void {
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

test('or whereTranslationLike filters by translation', function (): void {
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

test('whereTranslationLike filters by translation and locale', function (): void {
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
    function (SortDirection|string $sortMethod, ?string $sortLocale, string $firstLocale, string $secondLocale): void {
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
    'uppercase asc'      => ['ASC', null, FakeLocale::Default, FakeLocale::Fallback],

    'desc without locale' => ['desc', null, FakeLocale::Fallback, FakeLocale::Fallback],
    'desc with locale'    => ['desc', FakeLocale::Default, FakeLocale::Fallback, FakeLocale::Fallback],
    'uppercase desc'      => ['DESC', null, FakeLocale::Fallback, FakeLocale::Fallback],

    'ascending enum'  => [SortDirection::Ascending, null, FakeLocale::Default, FakeLocale::Fallback],
    'descending enum' => [SortDirection::Descending, null, FakeLocale::Fallback, FakeLocale::Fallback],
]);

test('orderByTranslation rejects an invalid direction', function (): void {
    TestModel::query()->orderByTranslation(FakeValue::Title, 'sideways');
})->throws(InvalidArgumentException::class, 'Order direction');

test('orderByTranslation sorts by key asc even if locale is missing', function (): void {
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
