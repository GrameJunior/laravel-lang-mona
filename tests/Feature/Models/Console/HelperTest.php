<?php

declare(strict_types=1);

use LaravelLang\Config\Services\ModelLangConfig;
use LaravelLang\Exceptions\InvalidModelClassException;
use LaravelLang\Exceptions\InvalidModelCommandInputException;
use LaravelLang\Models\Console\ModelsHelperCommand;
use LaravelLang\Models\Eloquent\Translation;
use Workbench\App\Models\TestModel;
use Workbench\App\Models\User;

use function Pest\Laravel\artisan;

test('generate many models', function (): void {
    $path = sprintf(
        '%s/_ide_helper_models_%s.php',
        app(ModelLangConfig::class)->helpers,
        hash('xxh128', TestModel::class)
    );

    artisan(ModelsHelperCommand::class)->assertSuccessful()->run();

    expect($path)->toBeReadableFile();

    expect(file_get_contents($path))->toMatchSnapshot();
});

test('generate one model', function (): void {
    $path = sprintf(
        '%s/_ide_helper_models_%s.php',
        app(ModelLangConfig::class)->helpers,
        hash('xxh128', TestModel::class)
    );

    artisan(ModelsHelperCommand::class, [
        'model' => TestModel::class,
    ])->assertSuccessful()->run();

    expect($path)->toBeReadableFile();

    expect(file_get_contents($path))->toMatchSnapshot();
});

test('rejects a non-string model argument when generating helpers', function (): void {
    artisan(ModelsHelperCommand::class, ['model' => 123])->run();
})->throws(InvalidModelCommandInputException::class, 'The model argument must be a class name.');

test('rejects a configured translation class that is not a translation model', function (): void {
    config(['localization_models.suffix' => '']);

    artisan(ModelsHelperCommand::class, ['model' => User::class])->run();
})->throws(InvalidModelClassException::class, sprintf('Model `%s` must extend `%s`.', User::class, Translation::class));
