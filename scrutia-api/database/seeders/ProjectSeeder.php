<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    // Question do we generate tag seeder ? how to make it happens in the good order ?
    // Tags should go first
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory(10)->create();
    }
}
