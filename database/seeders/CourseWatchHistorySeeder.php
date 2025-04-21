<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CourseWatchHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Schema::hasTable('course_watch_histories')) {
            $user = 2; // Specific user ID

            $courses = [
                ['id' => 1, 'modules' => [1, 2]],
                ['id' => 2, 'modules' => [3]],
            ];

            $videos = [
                1 => ['duration' => 200],
                2 => ['duration' => 800],
                3 => ['duration' => 500],
            ];

            $startDate = Carbon::now()->subMonths(6);

            foreach ($courses as $course) {
                foreach ($course['modules'] as $module) {
                    foreach ($videos as $videoId => $video) {
                        $watchTime = rand(100, $video['duration']);
                        $watchedAt = $startDate->copy()->addDays(rand(1, 180));

                        // Insert into course_watch_histories
                        DB::table('course_watch_histories')->insert([
                            'user_id' => $user,
                            'course_id' => $course['id'],
                            'course_module_id' => $module,
                            'course_video_id' => $videoId,
                            'watch_time' => $watchTime,
                            'watched_at' => $watchedAt,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
}
