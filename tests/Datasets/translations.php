<?php

declare(strict_types=1);

dataset('translations', [
    'default' => [
        'config' => ['default'],
        'foo'    => ['Foo :attribute 0', 'Foo :attribute 1'],
        'bar'    => ['Bar :attribute 0', 'Bar :attribute 1'],
        'baz'    => ['Baz :attribute 0', 'Baz :attribute 1'],
    ],

    'inline' => [
        'config' => ['inline'],
        'foo'    => ['Foo :attribute 0', 'Foo field 1'],
        'bar'    => ['Bar :attribute 0', 'Bar field 1'],
        'baz'    => ['Baz :attribute 0', 'Baz field 1'],
    ],

    'empty' => [
        'config' => [],
        'foo'    => ['Foo :attribute 0', 'Foo :attribute 1'],
        'bar'    => ['Bar :attribute 0', 'Bar :attribute 1'],
        'baz'    => ['Baz :attribute 0', 'Baz :attribute 1'],
    ],
]);
