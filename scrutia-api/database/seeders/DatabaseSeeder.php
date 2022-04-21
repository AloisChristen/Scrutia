<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Question;
use App\Models\Version;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        Answer::factory(10)->create();
        Project::factory(10)->create();
        Question::factory(10)->create();
        Tag::factory(10)->create();
        Version::factory(10)->create();
        // \App\Models\User::factory(10)->create();
        // QUESTION: why not making user seeder specific ??
    }
}
