<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
            'tags:id,name',
        ])
            ->withCount([
                'modules',
                'modules as videos_count' => function ($query) {
                    $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id')
                        ->select(DB::raw('count(course_videos.id)'));
                },
            ])
            ->whereHas('modules');

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

        $courses = $query->where('status', 'approved')->paginate($request->per_page ?? 10);

        $courses->map(function ($course) {
            $course->is_bookmarked = $course->bookmarks->isNotEmpty();
            // $course->is_modules_exists = $course->modules->isNotEmpty();
            unset($course->bookmarks);
            // unset($course->modules);
            return $course;
        });

        if ($courses->isEmpty()) {
            return $this->error([], 'Course Not Found', 200);
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
            'modules.videos',
        ])->where('status', 'approved')->find($id);

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        $course->is_bookmarked = $course->bookmarks()->where('user_id', auth()->id())->exists();

        return $this->success($course, 'Course found successfully', 200);
    }

    public function courseProgress(int $courseId)
    {
        $course = Course::where('id', $courseId)
            ->withCount(['modules', 'progress', 'modules as videos_count' => function ($query) {
                $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id')
                    ->select(DB::raw('count(course_videos.id)'));
            }])
            ->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        $progress = $course->videos_count > 0 ? ($course->progress_count / $course->videos_count) * 100 : 0;

        $response = [
            'video_count' => $course->videos_count,
            'progress_count' => $course->progress_count,
            'progress' => $progress,
        ];

        return $this->success($response, 'Course Progress', 200);
    }

    public function getCategoryWiseCourses(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User not authenticated', 401);
        }

        $userId = $user->id;
        $take = $request->query('take');

        $categories = Category::with([
            'courses' => function ($query) use ($userId, $take) {
                $query->with([
                    'instructor:id,user_id',
                    'instructor.user:id,first_name,last_name,role',
                    'category:id,name',
                    'tags:id,name',
                ])
                    ->withCount([
                        'modules',
                        'modules as videos_count' => function ($query) {
                            $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id')
                                ->select(DB::raw('count(course_videos.id)'));
                        },
                        'bookmarks as is_bookmark' => function ($query) use ($userId) {
                            $query->where('user_id', $userId);
                        }
                    ])
                    ->whereHas('modules')
                    ->take($take);
            }
        ])
            ->where('status', 'active')
            ->whereHas('courses')
            ->get();

        if ($categories->isEmpty()) {
            return $this->error([], 'Category Not Found', 200);
        }

        return $this->success($categories, 'Category wise courses', 200);
    }
}
