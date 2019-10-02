<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Ollieread\Articles\Models\Series;
use Ollieread\Articles\Operations\GetCategory;
use Ollieread\Articles\Operations\GetOrCreateTags;
use Ollieread\Articles\Operations\GetTopics;
use Ollieread\Articles\Operations\GetVersions;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = require __DIR__ . '/data/series.php';

        foreach ($data as $row) {
            $series = (Series::query()->where('slug', '=', $row['slug'])->first() ?? new Series)
                ->fill(Arr::except($row, ['category', 'topics', 'versions', 'tags']));

            if ($row['category']) {
                $category = (new GetCategory)->setSlug($row['category'])->perform();
                $series->category()->associate($category);
            }

            $series->content = file_get_contents(__DIR__ . '/data/series/' . $row['slug'] . '.md');

            if ($series->save()) {
                if ($row['topics']) {
                    $topics = (new GetTopics)->setSlugs($row['topics'])->perform();
                    $series->topics()->sync($topics->pluck('id'));
                }

                if ($row['versions']) {
                    $versions = (new GetVersions)->setSlugs($row['versions'])->perform();
                    $series->versions()->sync($versions->pluck('id'));
                }

                if ($row['tags']) {
                    $tags = (new GetOrCreateTags)->setNames($row['tags'])->perform();
                    $series->tags()->sync($tags->pluck('id'));
                }

                $this->command->info(sprintf('Series %s added/updated', $series->name));
            }
        }
    }
}
