<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(TopicSeeder::class);
        $this->call(VersionSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(SeriesSeeder::class);
        $this->call(ArticleSeeder::class);
        //$this->call(CourseSeeder::class);
        //$this->call(PackageSeeder::class);
    }
}
