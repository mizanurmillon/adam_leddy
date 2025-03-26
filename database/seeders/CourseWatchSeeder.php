<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CourseWatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        if (Schema::hasTable('course_watches') && Schema::hasTable('course_progress')) {
            $user = 2; // Specific user ID
            $courses = [
                ['id' => 1, 'modules' => [1, 2]],
                ['id' => 2, 'modules' => [3]],
                ['id' => 3, 'modules' => [4]],
            ];
            $videos = [
                1 => ['duration' => 1200],
                2 => ['duration' => 1800],
                3 => ['duration' => 1500],
            ];

            $startDate = Carbon::now()->subMonths(6);

            foreach ($courses as $course) {
                foreach ($course['modules'] as $module) {
                    foreach ($videos as $videoId => $video) {
                        $watchTime = rand(600, $video['duration']);
                        $lastWatchedAt = $startDate->copy()->addDays(rand(1, 180));

                        // Insert watch record
                        DB::table('course_watches')->insert([
                            'user_id' => $user,
                            'course_id' => $course['id'],
                            'course_module_id' => $module,
                            'course_video_id' => $videoId,
                            'watch_time' => $watchTime,
                            'last_watched_at' => $lastWatchedAt,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        // Randomly mark progress as finished
                        if (rand(0, 1)) {
                            DB::table('course_progress')->insert([
                                'user_id' => $user,
                                'course_id' => $course['id'],
                                'course_module_id' => $module,
                                'course_video_id' => $videoId,
                                'finished_at' => $lastWatchedAt,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }
}
