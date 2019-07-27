<?php

use Illuminate\Database\Seeder;
use Ollieread\Articles\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = require __DIR__ . '/data/categories.php';

        foreach ($data as $row) {
            $category = (Category::query()->where('slug', '=', $row['slug'])->first() ?? new Category)
                ->fill($row);

            if ($category->save()) {
                $this->command->info(sprintf('Category %s added/updated', $category->name));
            }
        }
    }
}
