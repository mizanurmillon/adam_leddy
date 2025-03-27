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
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 2,
                    'title' => 'Vue.js for Beginners',
                    'description' => 'Get started with Vue.js and build interactive web applications.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 3,
                    'title' => 'Advanced PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
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
                ['course_module_id' => 1, 'video_url' => 'https://youtu.be/UsLXGkAD2mo?si=kra4yQ_a7wb5Jv6K', 'file_url' => null, 'duration' => 1200, 'created_at' => now(), 'updated_at' => now()],
                ['course_module_id' => 2, 'video_url' => 'https://youtu.be/7NOOgEiKTq8?si=fDjAIfa5i_0I9nke', 'file_url' => null, 'duration' => 1800, 'created_at' => now(), 'updated_at' => now()],
                ['course_module_id' => 3, 'video_url' => 'https://youtu.be/01l0s1iR_ZI?si=CCaWaAjYZsIeAUZX', 'file_url' => null, 'duration' => 1500, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Schema::hasTable('course_tags')) {
            DB::table('course_tags')->insert([
                ['course_id' => 1, 'tag_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 2, 'tag_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 3, 'tag_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
