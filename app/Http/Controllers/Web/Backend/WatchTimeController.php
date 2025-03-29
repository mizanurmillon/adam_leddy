<?php
namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Instructor;

class WatchTimeController extends Controller
{
    public function index()
    {
        $courses = Course::with([
            'instructor:id,user_id',
            'instructor.user:id,first_name,last_name,role',
            'category:id,name',
            'tags:id,name',
            'courseWatches',
        ])
        ->withSum('courseWatches', 'watch_time') 
        ->orderByDesc('course_watches_sum_watch_time') 
        ->limit(3)
        ->get();

        $instructors = Instructor::with('user')->whereHas('courses.courseWatches')
        ->with(['courses.courseWatches'])
        ->get()
        ->map(function ($instructor) {
            return [
                'name' => $instructor->user->first_name . ' ' . $instructor->user->last_name,
                'watch_time' => number_format(
                    $instructor->courses->sum(function ($course) {
                        return $course->courseWatches->sum('watch_time') / 3600; 
                    }), 
                    2 
                ),
            ];
        });


        return view('backend.layouts.watch_time.index', compact('courses', 'instructors'));
    }

}
