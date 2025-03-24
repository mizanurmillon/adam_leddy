<?php

namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Instructor::create([
            'user_id' => 3,
            'category_id' => 1,
            'bio' => 'Experienced web developer and instructor.',
            'expertise' => 'Data Security, Cloud Computing, AI'
        ]);
    }
}
