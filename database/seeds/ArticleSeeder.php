<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Ollieread\Articles\Models\Article;
use Ollieread\Articles\Operations\GetCategoryBySlug;
use Ollieread\Articles\Operations\GetOrCreateTagsByName;
use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Articles\Operations\GetVersions;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = require __DIR__ . '/data/articles.php';

        foreach ($data as $row) {
            $article = (Article::query()->where('slug', '=', $row['slug'])->first() ?? new Article)
                ->fill(Arr::except($row, ['category', 'topics', 'versions', 'tags']));

            if ($row['category']) {
                $category = (new GetCategoryBySlug)->setSlug($row['category'])->perform();
                $article->category()->associate($category);
            }

            $article->content = file_get_contents(__DIR__ . '/data/articles/' . $row['slug'] . '.md');

            if ($article->save()) {
                if ($row['topics']) {
                    $topics = (new GetTopics)->setSlugs($row['topics'])->perform();
                    $article->topics()->sync($topics->pluck('id'));
                }

                if ($row['versions']) {
                    $versions = (new GetVersions)->setSlugs($row['versions'])->perform();
                    $article->versions()->sync($versions->pluck('id'));
                }

                if ($row['tags']) {
                    $tags = (new GetOrCreateTagsByName)->setNames($row['tags'])->perform();
                    $article->tags()->sync($tags->pluck('id'));
                }

                $this->command->info(sprintf('Article %s added/updated', $article->name));
            }
        }
    }
}
