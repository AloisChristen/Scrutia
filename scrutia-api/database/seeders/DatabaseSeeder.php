<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Question;
use App\Models\User;
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
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(VersionSeeder::class);
        //$this->call(QuestionSeeder::class);
        //$this->call(AnswerSeeder::class);
        // $this->call(LikeSeeder::class);
    }
}
