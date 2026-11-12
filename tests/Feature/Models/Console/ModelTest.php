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
    $helper = sprintf('%s/_ide_helper_models_%s.php', app(ModelConfig::class)->helpers, md5('App\Models\Some'));

    $migrations = (new Filesystem())->glob(database_path('migrations/*_create_some_translations_table.php'));

    expect($migrations)->toHaveCount(1);

    $fillableMatches = collect(['locale', 'title', 'description'])
        ->map(fn (string $column) => sprintf('(\s{8}\'%s\',\r?\n?)', $column))
        ->implode('');

    $castsMatches = collect(['title', 'description'])
        ->map(fn (string $column) => sprintf('(\s{8}\'%s\'\s=>\sTrimCast::class,\r?\n?)', $column))
        ->implode('');

    expect(file_get_contents($model))
        ->toContain('App\Models')
        ->toContain('class SomeTranslation extends Translation')
        ->toMatch(sprintf('/protected\s\$fillable\s=\s\[\r?\n?%s\s+];/', $fillableMatches))
        ->toMatch(
            sprintf(
                '/\s{4}protected\s+\$casts\s+=\s+\[\r?\n?%s\r?\n?\s{4}\]/',
                $castsMatches
            )
        );

    expect(file_get_contents($migrations[0]))
        ->toContain('Schema::create(\'some_translations\'')
        ->toContain('Schema::dropIfExists(\'some_translations\')')
        ->toContain('$table->string(\'title\')->nullable()')
        ->toContain('$table->string(\'description\')->nullable()')
        ->toContain('->constrained(\'somes\')');

    expect($helper)->toBeReadableFile();
});
