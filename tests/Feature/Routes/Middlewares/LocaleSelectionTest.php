<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Casts\Attribute;
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
): void {
    Auth::guard('web')->setUser(new User(['locale' => 'fr']));
    Auth::guard('foo')->setUser(new class extends User {
        protected function locale(): Attribute
        {
            return Attribute::make(get: fn(): Locale => Locale::German);
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

test('a later locale source wins and its choice is restored from the session', function (): void {
    Route::middleware('web')->group(function (): void {
        Route::localizedGroup(function (): void {
            Route::get('locale-selection', fn (): array => ['locale' => app()->getLocale()])->name('locale.selection');
        });
    });

    getJson('/en/locale-selection', ['Accept-Language' => 'de'])
        ->assertOk()
        ->assertJsonPath('locale', 'de')
        ->assertSessionHas('Accept-Language', 'de');

    Event::assertDispatched(fn (LocaleHasBeenSetEvent $event): bool => $event->locale->code === 'en');
    Event::assertDispatched(fn (LocaleHasBeenSetEvent $event): bool => $event->locale->code === 'de');

    app()->setLocale('fr');

    getJson('/locale-selection')->assertOk()->assertJsonPath('locale', 'de');
});
