<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Routes\Events\LocaleHasBeenSetEvent;
use Workbench\App\Constants\FakeTranslation;
use Workbench\App\Models\User;

use function Pest\Laravel\getJson;

test('the middleware uses the requested guard and accepts a locale enum from the model', function (
    string $route,
    string $translation
) {
    Auth::guard('web')->setUser(new User(['locale' => 'fr']));
    Auth::guard('foo')->setUser(new class extends User {
        public function getLocaleAttribute(): Locale
        {
            return Locale::German;
        }
    });

    app()->setLocale('en');

    getJson(route($route, ['key' => 'test']))
        ->assertOk()
        ->assertJsonPath('test', $translation);
})->with([
    'default guard' => ['via.model.default', FakeTranslation::French],
    'named guard'   => ['via.model.guard', FakeTranslation::German],
]);

test('a later locale source wins and its choice is restored from the session', function () {
    Route::middleware('web')->group(function () {
        Route::localizedGroup(function () {
            Route::get('locale-selection', fn () => ['locale' => app()->getLocale()])->name('locale.selection');
        });
    });

    getJson('/en/locale-selection', ['Accept-Language' => 'de'])
        ->assertOk()
        ->assertJsonPath('locale', 'de')
        ->assertSessionHas('Accept-Language', 'de');

    Event::assertDispatched(LocaleHasBeenSetEvent::class, fn ($event) => $event->locale->code === 'en');
    Event::assertDispatched(LocaleHasBeenSetEvent::class, fn ($event) => $event->locale->code === 'de');

    app()->setLocale('fr');

    getJson('/locale-selection')->assertOk()->assertJsonPath('locale', 'de');
});
