<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\User;

class CourseManagementController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'default');
        $courses = Course::with(['category'])
            ->where('status', 'approved')
            ->withCount([
                'courseWatches as total_watch_time' => function ($query) {
                    $query->selectRaw('COALESCE(SUM(watch_time), 0)');
                }
            ])
            ->when($sort === 'recent', function ($query) {
                $query->orderByDesc('created_at');
            })
            ->when($sort === 'watch-high', function ($query) {
                $query->orderByDesc('total_watch_time');
            })
            ->when($sort === 'watch-low', function ($query) {
                $query->orderBy('total_watch_time');
            })
            ->paginate(10);


        $instructorNames = [];
        foreach ($courses as $course) {
            $instructor = Instructor::where('id', $course->instructor_id)->first();
            if ($instructor) {
                $user = User::where('id', $instructor->user_id)->first();
                if ($user) {
                    $instructorNames[$course->id] = $user->first_name . ' ' . $user->last_name;
                }
            }
        }
        
        return view('backend.layouts.courses.index', compact('courses', 'sort', 'instructorNames'));
    }
    public function content()
    {
        return view('backend.layouts.courses.content');
    }
}
