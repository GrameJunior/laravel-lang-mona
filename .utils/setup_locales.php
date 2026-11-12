<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

require __DIR__ . '/vendor/autoload.php';

$projects = [
    __DIR__ . '/../src/Actions/',
    __DIR__ . '/../src/Attributes/',
    __DIR__ . '/../src/HttpStatuses/',
    __DIR__ . '/../src/MoonShine/',
    __DIR__ . '/../src/StarterKits/',
    __DIR__ . '/../src/Lang/',
];

$filesystem = new Filesystem();

foreach ($projects as $project) {
    dump($project . '...');

    $composer = new Composer($filesystem, $project);

    $composer->modify(function (array $data): array {
        unset(
            $data['funding'],
            $data['require-dev'],
            $data['autoload'],
            $data['autoload-dev'],
            $data['config']['allow-plugins'],
            $data['extra']
        );

        $data['require'] = [
            'require' => '^8.3',

            'laravel-lang/publisher' => 'self.version',
        ];

        return $data;
    });

    foreach ([
        $project . 'locales',
        $project . 'src',
    ] as $dir) {
        $filesystem->deleteDirectory($dir);
    }

    $filesystem->delete([
        $project . '.gitignore',
        $project . 'src',
    ]);

    file_put_contents(
        $project . '.gitattributes',
        <<<TXT
* text=auto

.github/ export-ignore
.gitattributes export-ignore

TXT
    );
}
