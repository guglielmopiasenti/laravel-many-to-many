<?php

namespace Database\Seeders;

use App\Models\Technology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technologies = [
            ['label' => 'HTML', 'color' => '#F1652A'],
            ['label' => 'CSS', 'color' => '#2965F1'],
            ['label' => 'ES6', 'color' => '#F7DF1E'],
            ['label' => 'Bootstrap', 'color' => '#7952B3'],
            ['label' => 'PHP', 'color' => '#4F5B93'],
            ['label' => 'SQL', 'color' => '#E38A00'],
            ['label' => 'Laravel', 'color' => '#FF2D20'],
            ['label' => 'VueJS', 'color' => '#4FC08D'],
        ];
        foreach($technologies as $technology) {
            $new_technology = new Technology();
            $new_technology->label = $technology['label'];
            $new_technology->color = $technology['color'];
            $new_technology->save();
        }
    }
}
