<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelLang\Config\LangConfigServiceProvider;
use LaravelLang\LocaleList\Locale;
use LaravelLang\Locales\Facades\Locales;
use LaravelLang\NativeCountryNames\CountryNames;
use LaravelLang\NativeCurrencyNames\CurrencyNames;
use LaravelLang\NativeLocaleNames\LocaleNames;
use LaravelLang\Routes\LangRoutesServiceProvider;

use function LaravelLang\Routes\localizedRoute;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/laravel-lang/mona/src/Routes/src/helpers.php';

function verify(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

$base = sys_get_temp_dir() . '/mona-compatibility-' . bin2hex(random_bytes(8));
$files = new Filesystem();
$files->ensureDirectoryExists($base . '/bootstrap/cache');

try {
    $app = Application::configure($base)
        ->withProviders([LangConfigServiceProvider::class, LangRoutesServiceProvider::class])
        ->create();

    $app->make(Kernel::class)->bootstrap();
    $app->setLocale('fr');
    $app->setFallbackLocale('de');

    config()->set([
        'localization_main.aliases'          => ['de' => 'de_DE'],
        'localization_routes.names.parameter' => 'language',
        'session.driver'                     => 'array',
    ]);

    verify(Locales::isInstalled('de_DE'), 'The fallback locale alias should be installed.');
    verify(! Locales::uninstalled()->contains('de_DE'), 'The installed alias should be absent from uninstalled locales.');
    verify(app(LocaleNames::class)->one('de_DE', Locale::French)->localized === 'Französisch', 'Locale name lookup failed.');
    verify(app(CountryNames::class)->one('de_DE', Locale::French)->code === 'FR', 'Country name lookup failed.');
    verify(app(CurrencyNames::class)->one('de_DE', Locale::French)->code === 'EUR', 'Currency name lookup failed.');

    $names = app(LocaleNames::class)->all('de_DE', SortDirection::Descending);
    verify($names->first()->localized > $names->last()->localized, 'Descending locale name sort failed.');

    Route::localizedGroup(function () {
        Route::get('catalog/{id}', fn (string $id) => $id . ':' . app()->getLocale())->name('catalog.show');
    });
    Route::getRoutes()->refreshNameLookups();

    $url = localizedRoute('catalog.show', ['id' => 'book'], absolute: false);
    verify($url === '/de_DE/catalog/book', 'The URL should use the configured locale alias.');

    $response = $app->handle(Request::create($url));
    verify($response->getStatusCode() === 200 && $response->getContent() === 'book:de', 'The localized route should select the requested locale.');

    echo 'Public APIs passed on Laravel ' . $app->version() . PHP_EOL;
} finally {
    $files->deleteDirectory($base);
}
