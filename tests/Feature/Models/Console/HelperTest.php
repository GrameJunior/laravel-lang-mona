<?php

declare(strict_types=1);

use LaravelLang\Config\Services\ModelConfig;
use LaravelLang\Models\Console\ModelsHelperCommand;
use Workbench\App\Models\TestModel;

use function Pest\Laravel\artisan;

test('generate many models', function () {
    $path = sprintf(
        '%s/_ide_helper_models_%s.php',
        app(ModelConfig::class)->helpers,
        hash('xxh128', TestModel::class)
    );

    artisan(ModelsHelperCommand::class)->assertSuccessful()->run();

    expect($path)->toBeReadableFile();

    expect(file_get_contents($path))->toMatchSnapshot();
});

test('generate one model', function () {
    $path = sprintf(
        '%s/_ide_helper_models_%s.php',
        app(ModelConfig::class)->helpers,
        hash('xxh128', TestModel::class)
    );

    artisan(ModelsHelperCommand::class, [
        'model' => TestModel::class,
    ])->assertSuccessful()->run();

    expect($path)->toBeReadableFile();

    expect(file_get_contents($path))->toMatchSnapshot();
});
