<?php

declare(strict_types=1);

use LaravelLang\Toolkit\Commands\SyncCommand;
use Symfony\Component\Console\Command\Command;
use Tests\Fixtures\ProjectNamespace;

arch('protect')
    ->expect(ProjectNamespace::All)
    ->extending(Command::class)
    ->toBeFinal();

arch('handle')
    ->expect(ProjectNamespace::All)
    ->extending(Command::class)
    ->toBeInvokable();

arch('naming')
    ->expect(ProjectNamespace::All)
    ->extending(Command::class)
    ->toHaveSuffix('Command');

arch('toolkit protect')
    ->expect('LaravelLang\Toolkit\Commands')
    ->toBeFinal()
    ->ignoring(SyncCommand::class);

arch('toolkit naming')
    ->expect('LaravelLang\Toolkit\Commands')
    ->toHaveSuffix('Command');

arch('toolkit invokable')
    ->expect('LaravelLang\Toolkit\Commands')
    ->toBeInvokable()
    ->ignoring(SyncCommand::class);
