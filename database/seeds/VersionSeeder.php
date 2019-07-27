<?php

use Illuminate\Database\Seeder;
use Ollieread\Core\Models\Version;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = require __DIR__ . '/data/versions.php';

        foreach ($data as $row) {
            $version = (Version::query()->where('slug', '=', $row['slug'])->first() ?? new Version())
                ->fill($row);

            if ($version->save()) {
                $this->command->info(sprintf('Version %s added/updated', $version->name));
            }
        }
    }
}
