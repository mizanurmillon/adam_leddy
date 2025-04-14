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
            ['name' => 'Programming', 'status' => 'active'],
            ['name' => 'Design', 'status' => 'active'],
            ['name' => 'Data Analysis', 'status' => 'active'],
            ['name' => 'Database', 'status' => 'active'],
            ['name' => 'AI','status' => 'inactive'],
            ['name' => 'Machine Learning','status' => 'inactive'],
            ['name' => 'Cloud Computing','status' => 'inactive'],
        ];

        DB::table('categories')->insert($categories);
    }
}
