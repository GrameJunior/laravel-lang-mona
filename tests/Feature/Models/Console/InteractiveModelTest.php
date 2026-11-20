<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\User;

use function Pest\Laravel\artisan;

test('generates a translation model from prompts and preserves the parent', function (string $name, array $answers, array $columns): void {
    $filesystem       = app(Filesystem::class);
    $path             = sys_get_temp_dir() . '/mona-model-prompt-' . Str::uuid();
    $models           = $path . '/Models';
    $source           = $models . '/' . $name . '.php';
    $class            = 'App\\Models\\' . $name;
    $translationClass = $class . 'Translation';

    $original = <<<PHP
        <?php

        namespace App\\Models;

        use Illuminate\\Database\\Eloquent\\Model;
        use LaravelLang\\Models\\HasTranslations;

        class $name extends Model
        {
            use HasTranslations;
        }
        PHP;

    try {
        $filesystem->ensureDirectoryExists($models);
        $filesystem->put($source, $original);
        require $source;

        app()->useDatabasePath($path . '/database');
        config([
            'localization_models.directories' => [$models, ...config('localization_models.directories')],
            'localization_models.helpers'     => $path . '/helpers',
        ]);

        $command = artisan('make:model-translation')->expectsChoice(
            'Select a model to create a translation repository:',
            $class,
            [$class, TestModel::class, User::class],
            strict: true,
        );

        foreach ($answers as $answer) {
            $command->expectsQuestion('Enter a column name or press Enter for continue', $answer);
        }

        $command->expectsQuestion('Enter a column name or press Enter for continue', null)
            ->assertSuccessful()
            ->run();

        expect((new $translationClass)->getFillable())->toBe(['locale', ...$columns])
            ->and($filesystem->get($source))->toBe($original);
    } finally {
        $filesystem->deleteDirectory($path);
    }
})->with([
    'custom columns'  => ['PromptedColumns', ['subtitle', 'summary'], ['subtitle', 'summary']],
    'default columns' => ['DefaultColumns', [], ['title', 'description']],
]);
