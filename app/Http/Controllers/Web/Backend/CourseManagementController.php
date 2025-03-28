<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Tag;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\CourseWatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CourseManagementController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $categories = Category::all();
    
        // Start with a query to filter by category if selected
        $query = Course::with('courseWatches', 'category');
    
        if (!empty($data['category']) && $data['category'] != 'undefined') {
            $query->where('category_id', $data['category']);
        }
    
        // Apply sorting at the database level
        if (!empty($data['sort']) && $data['sort'] !== "default") {
            if ($data['sort'] == 'watch-low') {
                $query->withSum('courseWatches', 'watch_time')->orderBy('course_watches_sum_watch_time', 'asc');
            }
            if ($data['sort'] == 'watch-high') {
                $query->withSum('courseWatches', 'watch_time')->orderBy('course_watches_sum_watch_time', 'desc');
            }
            if ($data['sort'] == 'recent') {
                $query->orderBy('created_at', 'desc');
            }
        }
    
        // Paginate results
        $courses = $query->paginate(10)->withQueryString(); // Preserve query params in pagination
    
        // Fetch instructor names
        $instructorNames = $courses->mapWithKeys(function ($course) {
            $instructor = Instructor::find($course->instructor_id);
            if ($instructor) {
                $user = User::find($instructor->user_id);
                return [$course->id => $user ? $user->first_name . ' ' . $user->last_name : ''];
            }
            return [$course->id => ''];
        });
    
        return view('backend.layouts.courses.index', compact('courses', 'categories', 'instructorNames'));
    }
    public function content(Request $request, $id)
    {
        $tags = Tag::all();
        $course = Course::with('instructor.user', 'modules.videos', 'modules.courseWatches', 'category', 'tags')
            ->where('id', $id)
            ->first();

        if (! $course) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        $moduleIds = $course->modules ? $course->modules->pluck('id')->toArray() : [];

        $monthlyWatchTime = [];
        if (! empty($moduleIds)) {
            $monthlyWatchTime = CourseWatch::whereIn('course_module_id', $moduleIds)
                ->selectRaw('MONTH(last_watched_at) as month, SUM(watch_time) as total_watch_time')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_watch_time', 'month')
                ->toArray();
        }

        // Ensure watchTimes has values for all 12 months
        $watchTimes = [];
        for ($i = 1; $i <= 12; $i++) {
            $watchTimes[] = isset($monthlyWatchTime[$i]) ? round($monthlyWatchTime[$i] / 3600, 2) : 0;
        }

        $topInstructor = Course::with([
            'instructor:id,user_id',
            'instructor.user:id,first_name,last_name,role',
            'category:id,name',
            'tags:id,name',
            'courseWatches',
        ])
        ->withCount('courseWatches as total_watch_time')
        ->orderByDesc('total_watch_time')
        ->first();

        $moduleIds = $topInstructor->modules ? $topInstructor->modules->pluck('id')->toArray() : [];

        $topMonthlyWatchTime = [];
        if (! empty($moduleIds)) {
            $topMonthlyWatchTime = CourseWatch::whereIn('course_module_id', $moduleIds)
                ->selectRaw('MONTH(last_watched_at) as month, SUM(watch_time) as total_watch_time')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_watch_time', 'month')
                ->toArray();
        }

        $topWatchTimes = [];
        for ($i = 1; $i <= 12; $i++) {
            $topWatchTimes[] = isset($topMonthlyWatchTime[$i]) ? round($topMonthlyWatchTime[$i] / 3600, 2) : 0;
        }

        return view('backend.layouts.courses.content', compact('course', 'watchTimes', 'tags', 'topInstructor', 'topWatchTimes'));
    }
}
