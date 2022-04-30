<?php

namespace Database\Seeders;

use App\Models\Version;
use Illuminate\Database\Seeder;

class VersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Version::factory(25)->create();
    }
}
