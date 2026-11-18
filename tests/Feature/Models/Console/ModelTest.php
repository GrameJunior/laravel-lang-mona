<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand as LaravelMakeModel;
use LaravelLang\Config\Services\ModelConfig;
use LaravelLang\Models\Console\ModelMakeCommand as PackageMakeModel;

use function Pest\Laravel\artisan;

$cleanUp = function () {
    $filesystem = new Filesystem();

    $filesystem->delete([
        app_path('Models/Some.php'),
        app_path('Models/SomeTranslation.php'),
        ...$filesystem->glob(database_path('migrations/*_create_some_translations_table.php')),
    ]);
};

beforeEach($cleanUp);
afterEach($cleanUp);

test('from scratch', function () {
    artisan(LaravelMakeModel::class, [
        'name' => 'Some',
    ])->assertSuccessful()->run();

    require_once app_path('Models/Some.php');

    artisan(PackageMakeModel::class, [
        'model'     => '\App\Models\Some',
        '--columns' => ['title', 'description'],
    ])->assertSuccessful()->run();

    $model  = app_path('Models/SomeTranslation.php');
    $helper =
        sprintf('%s/_ide_helper_models_%s.php', app(ModelConfig::class)->helpers, hash('xxh128', 'App\Models\Some'));

    $migrations = (new Filesystem())->glob(database_path('migrations/*_create_some_translations_table.php'));

    expect($migrations)->toHaveCount(1);

    expect(file_get_contents($model))->toMatchSnapshot();
    expect(file_get_contents($migrations[0]))->toMatchSnapshot();

    expect($helper)->toBeReadableFile();
});
