<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
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
        Project::factory(10)->create();
        Tag::factory(50)->create();

        $tags = Tag::all();

        Project::all()->each(function ($project) use ($tags) {
           $project->tags()->attach(
               $tags->random(rand(1,3))->pluck('id')->toArray()
           );
        });
    }
}
