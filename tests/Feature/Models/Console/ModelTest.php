<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand as LaravelMakeModel;
use LaravelLang\Config\Facades\Config;
use LaravelLang\Models\Console\ModelMakeCommand as PackageMakeModel;

use function Pest\Laravel\artisan;

beforeEach(fn () => (new Filesystem())->delete([
    base_path('app/Models/Some.php'),
    base_path('app/Models/SomeTranslation.php'),
]));

afterEach(function () {
    $filesystem = new Filesystem();

    $files = $filesystem->allFiles(database_path('migrations'));

    foreach ($files as $file) {
        if (str_contains($file->getFilename(), 'create_fixture')) {
            $filesystem->delete($file->getRealPath());
        }
    }
});

test('from scratch', function () {
    artisan(LaravelMakeModel::class, [
        'name' => 'Some',
    ])->run();

    artisan(PackageMakeModel::class, [
        'model'     => '\App\Models\Some',
        '--columns' => ['title', 'description'],
    ])->run();

    $model  = base_path('app/Models/SomeTranslation.php');
    $helper = sprintf('%s/_ide_helper_models_%s.php', Config::shared()->models->helpers, md5('App\Models\Some'));

    $migrations = (new Filesystem())->files(database_path('migrations'));

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

    expect($migrations[0]->getContents())
        ->toContain('Schema::create(\'some_translations\'')
        ->toContain('Schema::dropIfExists(\'some_translations\')')
        ->toContain('$table->string(\'title\')->nullable()')
        ->toContain('$table->string(\'description\')->nullable()')
        ->toContain('->constrained(\'somes\')');

    expect($helper)->toBeReadableFile();
});
