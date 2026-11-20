<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Lang;
use Workbench\App\Constants\FakeLocale;

beforeEach(function (): void {
    config()->set('app.locale', FakeLocale::Incorrect1);
});
test('php', function (): void {
    expect(__('custom.failed'))->toMatchSnapshot();
    expect(__('custom.password'))->toMatchSnapshot();
});
test('json', function (): void {
    expect(__('Remember Me'))->toMatchSnapshot();
    expect(__('You are logged in!'))->toMatchSnapshot();
});

test('a missing key is returned verbatim', function (): void {
    expect(__('Missing translation'))->toBe('Missing translation');
});

test('fallback JSON replaces placeholders and respects explicit opt-out', function (): void {
    file_put_contents(lang_path('de.json'), json_encode(['Welcome :name' => 'Willkommen :name'], JSON_THROW_ON_ERROR));

    expect(__('Welcome :name', ['name' => 'Mona']))->toBe('Willkommen Mona')
        ->and(Lang::get('Welcome :name', ['name' => 'Mona'], fallback: false))->toBe('Welcome Mona');
});

test('fallback JSON takes precedence over current PHP when fallback is enabled', function (): void {
    app()->setLocale('fr');
    file_put_contents(lang_path('de.json'), json_encode(['collision.greeting' => 'JSON :name'], JSON_THROW_ON_ERROR));
    file_put_contents(lang_path('fr/collision.php'), '<?php return ["greeting" => "PHP :name"];');

    expect(__('collision.greeting', ['name' => 'Mona']))->toBe('JSON Mona')
        ->and(Lang::get('collision.greeting', ['name' => 'Mona'], fallback: false))->toBe('PHP Mona');
});
