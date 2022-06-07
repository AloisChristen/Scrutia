<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Status;
use App\Models\Tag;
use App\Models\Version;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Project::factory(20)->create();
        Tag::factory(50)->create();

        $tags = Tag::all();

        Project::all()->each(function ($project) use ($tags) {
            Version::create([
                'number' => 1,
                'project_id' => $project->id,
                'user_id' => $project->user->id,
                'description' => 'Init project',
            ]);

           $project->tags()->attach(
               $tags->random(rand(1,9))->pluck('id')->toArray()
           );
        });
    }
}
