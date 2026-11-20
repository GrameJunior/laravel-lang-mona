<?php

declare(strict_types=1);

use App\Models\Article;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\UniqueConstraintViolationException;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$article = Article::create([
    'title'       => '  First article  ',
    'description' => '  Article description  ',
]);

$article->setTranslation('title', 'Erster Artikel', 'de')->save();
Article::create(['title' => 'Second article']);

$stored = Article::findOrFail($article->getKey());

$result = [
    'title'       => $stored->title,
    'description' => $stored->description,
    'germanTitle' => $stored->getTranslation('title', 'de'),
    'locales'     => $stored->translationsRaw()->orderBy('locale')->pluck('locale')->all(),
    'uniquePair'  => false,
];

try {
    $stored->translationsRaw()->create(['locale' => 'en', 'title' => 'Duplicate']);
} catch (UniqueConstraintViolationException) {
    $result['uniquePair'] = true;
}

echo json_encode($result, JSON_THROW_ON_ERROR);
