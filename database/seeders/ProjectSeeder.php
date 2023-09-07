<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Faker $faker): void
    {
        for ($i = 1; $i <= 50; $i++) {
            $project = new Project();
            $project->name = $faker->word();
            $project->type_id = $faker->numberBetween(1, 5);
            $project->github_url = url('https://github.com/'); 
            $project->screenshot_path = $faker->imageUrl(640, 480, 'animals', true);
            $project->description = $faker->paragraph();
            $project->technologies = $faker->currencyCode(); 
            $project->is_featured = $faker->boolean();
            $project->save();
        }
    }
}
