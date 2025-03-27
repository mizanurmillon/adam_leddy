<?php
namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;

class RevenueController extends Controller
{
    public function index()
    {
        $courses = Course::with([
            'instructor:id,user_id',
            'instructor.user:id,first_name,last_name,role',
            'category:id,name',
            'tags:id,name',
            'courseWatches',
        ])->limit(3)->get();

        // Prepare the data for the chart
        $courseData = $courses->map(function ($course) {
            
            $watchTime = number_format($course->courseWatches->sum('watch_time') / 3600, 2); 
        
            return [
                'name' => $course->title, 
                'watch_time' => $watchTime, 
            ];
        });

        return view('backend.layouts.revenue.index', compact('courses', 'courseData'));
    }
}
