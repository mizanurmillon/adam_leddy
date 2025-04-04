<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        if (Schema::hasTable('courses')) {
            DB::table('courses')->insert([
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Introduction to Laravel',
                    'description' => 'Learn the basics of Laravel framework.',
                    'thumbnail' => 'backend/assets/images/card1.png',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 2,
                    'title' => 'Vue.js for Beginners',
                    'description' => 'Get started with Vue.js and build interactive web applications.',
                    'thumbnail' => 'backend/assets/images/card2.png',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 3,
                    'title' => 'Advanced PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/card1.png',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        if (Schema::hasTable('course_modules')) {
            DB::table('course_modules')->insert([
                ['course_id' => 1, 'module_title' => 'Getting Started', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 1, 'module_title' => 'Routing & Middleware', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 2, 'module_title' => 'Vue Basics', 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 3, 'module_title' => 'Advanced PHP Concepts', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Schema::hasTable('course_videos')) {
            DB::table('course_videos')->insert([
                ['course_module_id' => 1, 'video_url' => 'https://player.vimeo.com/video/1072434276?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1', 'file_url' => null, 'duration' => 1200, 'created_at' => now(), 'updated_at' => now()],
                ['course_module_id' => 1, 'video_url' => 'https://player.vimeo.com/video/1072434276?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1', 'file_url' => null, 'duration' => 1200, 'created_at' => now(), 'updated_at' => now()],
                ['course_module_id' => 2, 'video_url' => 'https://player.vimeo.com/video/1072434886?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1', 'file_url' => null, 'duration' => 1800, 'created_at' => now(), 'updated_at' => now()],
                ['course_module_id' => 3, 'video_url' => 'https://player.vimeo.com/video/1072434276?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1', 'file_url' => null, 'duration' => 1500, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
