<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Trending'],
            ['name' => 'Newly Added'],
            ['name' => 'Staff Pick'],
        ];

        DB::table('tags')->insert($tags);
    }
}
