<?php

use Illuminate\Database\Seeder;
use Ollieread\Core\Models\Topic;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = require __DIR__ . '/data/topics.php';

        foreach ($data as $row) {
            $topic = (Topic::query()->where('slug', '=', $row['slug'])->first() ?? new Topic)
                ->fill($row);

            if ($topic->save()) {
                $this->command->info(sprintf('Topic %s added/updated', $topic->name));
            }
        }
    }
}
