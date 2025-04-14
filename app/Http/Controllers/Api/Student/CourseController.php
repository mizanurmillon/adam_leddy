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
            ->withCount(['modules', 'progress'])
            ->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        $progress = $course->modules_count > 0 ? ($course->progress_count / $course->modules_count) * 100 : 0;

        return $this->success($progress, 'Course Progress', 200);
    }

    public function getCategoryWiseCourses()
    {
        $categories = Category::with([
            'courses.modules.videos',
            'courses.tags',
            'courses.instructor:id,user_id',
            'courses.instructor.user:id,first_name,last_name,role',
            'courses.bookmarks',
        ])
            ->where('status', 'active')
            ->get();

        if ($categories->isEmpty()) {
            return $this->error([], 'Category Not Found', 200);
        }

        // Filter out categories that have NO courses with modules
        $categories = $categories->filter(function ($category) {
            return $category->courses->contains(function ($course) {
                return $course->modules->isNotEmpty();
            });
        });

        // If filtered result is empty
        if ($categories->isEmpty()) {
            return $this->error([], 'No categories with course modules found', 200);
        }

        // Process mapping
        $categories = $categories->map(function ($category) {
            $category->course = $category->courses->filter(function ($course) {
                return $course->modules->isNotEmpty(); // remove courses with no modules
            })->map(function ($course) {
                $course->module_count = $course->modules->count();

                $course->videos_count = $course->modules->reduce(function ($carry, $module) {
                    return $carry + $module->videos->count();
                }, 0);

                $course->is_bookmarked = $course->bookmarks->isNotEmpty();

                unset($course->modules);
                unset($course->bookmarks);
                return $course;
            });

            unset($category->courses);
            return $category;
        });

        return $this->success($categories->values(), 'Category wise courses', 200);
    }

}
