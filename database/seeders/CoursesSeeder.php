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
                    'title' => 'PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced HTML & CSS',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced React.js',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced Database', 
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced HTML & CSS',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced React.js',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced Database', 
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced HTML & CSS',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced React.js',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced Database', 
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP Development',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/3.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced HTML & CSS',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced React.js',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/1.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced PHP',
                    'description' => 'Deep dive into PHP best practices and advanced topics.',
                    'thumbnail' => 'backend/assets/images/thumbnail/2.jpg',
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'instructor_id' => 1,
                    'category_id' => 1,
                    'title' => 'Advanced Database', 
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
                ['course_id' => 1, 'module_title' => 'Getting Started', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 1, 'module_title' => 'Routing & Middleware', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 2, 'module_title' => 'Vue Basics', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 3, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 4, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 5, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 6, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 7, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 8, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 9, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 10, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 11, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 12, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 13, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 14, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 15, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 17, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 18, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 19, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 20, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
                ['course_id' => 21, 'module_title' => 'Advanced PHP Concepts', 'file_url' => null, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        if (Schema::hasTable('course_videos')) {
            DB::table('course_videos')->insert([
                [
                    'course_module_id' => 1,
                    'video_title' => 'Introduction to Laravel',
                    'video_url' => 'https://player.vimeo.com/video/1074534457?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1', 'duration' => 200, 'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'course_module_id' => 1,
                    'video_title' => 'Routing & Middleware',
                    'video_url' => 'https://player.vimeo.com/video/1074536144?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1','duration' => 100, 'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'course_module_id' => 2,
                    'video_title' => 'Vue Basics',
                    'video_url' => 'https://player.vimeo.com/video/1074536144?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1','duration' => 1800,
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'course_module_id' => 2,
                    'video_title' => 'Vue Basics',
                    'video_url' => 'https://player.vimeo.com/video/1074536144?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1','duration' => 1800,
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'course_module_id' => 3, 
                    'video_title' => 'Advanced PHP Concepts',
                    'video_url' => 'https://player.vimeo.com/video/1074536144?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1','duration' => 1500, 'created_at' => now(), 
                    'updated_at' => now()
                ],
            ]);
        }
    }
}
