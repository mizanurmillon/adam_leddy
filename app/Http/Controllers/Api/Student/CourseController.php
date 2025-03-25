<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use ApiResponse;
    public function getCourse(Request $request)
    {
        $query = Course::with([
            'instructor:id,user_id',
            'instructor.user:id,first_name,last_name,role',
            'category:id,name',
            'tags:id,name'
        ])
            ->withCount(['modules', 'modules as videos_count' => function ($query) {
                $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id')
                    ->select(DB::raw('count(course_videos.id)'));
            }]);

        // Filter by Tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($query) use ($request) {
                $query->where('name', $request->tag);
            });
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }

        // Search by Title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Search by instructor_id
        if ($request->filled('instructor_id')) {
            $query->where('instructor_id', $request->instructor_id);
        }

        $courses = $query->paginate($request->per_page ?? 10);

        $courses->map(function ($course) {
            $course->is_bookmarked = $course->bookmarks->isNotEmpty(); // If bookmarks exist, set flag to true
            unset($course->bookmarks); // Remove bookmarks relationship from response
            return $course;
        });

        if ($courses->isEmpty()) {
            return $this->error([], 'Course Not Found', 404);
        }

        return $this->success($courses, 'Course fetch successfully', 200);
    }

    public function getCourseDetails(int $id)
    {
        $course = Course::with([
            'instructor.user:id,first_name,last_name,role,avatar',
            'category:id,name',
            'tags:id,name',
            'modules' => function ($query) {
                $query->withCount(['videos']);
            },
            'modules.videos'
        ])->find($id);

        $course->is_bookmarked = $course->bookmarks()->where('user_id', auth()->id())->exists();

        if (!$course) {
            return $this->error([], 'Course Not Found', 404);
        }

        return $this->success($course, 'Course found successfully', 200);
    }
}
