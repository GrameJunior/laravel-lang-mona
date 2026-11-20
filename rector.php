<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector;
use Rector\Config\RectorConfig;
use RectorLaravel\Rector\Class_\AppendsPropertyToAppendsAttributeRector;
use RectorLaravel\Rector\Class_\ConnectionPropertyToConnectionAttributeRector;
use RectorLaravel\Rector\Class_\DescriptionPropertyToDescriptionAttributeRector;
use RectorLaravel\Rector\Class_\FillablePropertyToFillableAttributeRector;
use RectorLaravel\Rector\Class_\RemoveModelPropertyFromFactoriesRector;
use RectorLaravel\Rector\Class_\SignaturePropertyToSignatureAttributeRector;
use RectorLaravel\Rector\Class_\TablePropertyToTableAttributeRector;
use RectorLaravel\Rector\Class_\WithoutTimestampsPropertyToWithoutTimestampsAttributeRector;
use RectorLaravel\Rector\FuncCall\AppToResolveRector;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\UseComponentPropertyWithinCommandsRector;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/rector.php',
    ])
    ->withFileExtensions(['php'])
    ->withParallel()
    ->withPreparedSets(
        deadCode        : true,
        codeQuality     : true,
        typeDeclarations: true,
        carbon          : true
    )
    ->withPhpSets(
        php83: true
    )
    ->withImportNames()
    ->withComposerBased(
        laravel: true
    )
    ->withSets([
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_TESTING,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_TYPE_DECLARATIONS,
    ])
    ->withConfiguredRule(RemoveDumpDataDeadCodeRector::class, [
        'dd',
        'die',
        'dump',
        'echo',
        'exit',
        'print_r',
        'printf',
        'var_dump',
    ])
    ->withRules([
        RemoveModelPropertyFromFactoriesRector::class,
        UseComponentPropertyWithinCommandsRector::class,
    ])
    ->withSkip([
        AppendsPropertyToAppendsAttributeRector::class,
        AppToResolveRector::class,
        ConnectionPropertyToConnectionAttributeRector::class,
        DescriptionPropertyToDescriptionAttributeRector::class,
        ExplicitNullableObjectInstanceRector::class,
        FillablePropertyToFillableAttributeRector::class,
        SignaturePropertyToSignatureAttributeRector::class,
        TablePropertyToTableAttributeRector::class,
        WithoutTimestampsPropertyToWithoutTimestampsAttributeRector::class,
    ]);
