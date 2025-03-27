<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

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
    public function content(Request $request)
    {
            // Get the course_id from the query string
        $courseId = $request->query('course_id');

        // Retrieve the course from the database using the course_id
        $course = Course::find($courseId);

        return view('backend.layouts.courses.content',compact('courseId','course'));
    }
}
