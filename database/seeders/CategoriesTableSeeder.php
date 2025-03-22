<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Programming'],
            ['name' => 'Design'],
            ['name' => 'Data Analysis'],
            ['name' => 'Database'],
            ['name' => 'AI'],
            ['name' => 'Machine Learning'],
            ['name' => 'Cloud Computing'],
        ];

        DB::table('categories')->insert($categories);
    }
}
