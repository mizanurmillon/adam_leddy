<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{
    use ApiResponse;

    public function toggleBookmark(Request $request, int $courseID)
    {

        $user = auth()->user();

        // Check if the product is already in favorites
        $bookmark = Bookmark::where('user_id', $user->id)->where('course_id', $courseID)->first();

        if ($bookmark) {
            $bookmark->delete();
            return $this->success([], 'Course removed from bookmark.', 200);
        } else {
            // If not, add to favorites
            Bookmark::create([
                'user_id' => $user->id,
                'course_id' => $courseID,
            ]);
            return $this->success([], 'Course added to bookmark.', 200);
        }
    }

    public function getBookmarks(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User not authenticated', 401);
        }

        // Fetch bookmarked courses with details
        $bookmarkedCourses = $user->bookmarks()->with([
            'course.instructor:id,user_id',
            'course.instructor.user:id,first_name,last_name,role',
            'course.category:id,name',
            'course.tags:id,name',
        ])
            ->addSelect([
                'modules_count' => Course::selectRaw('COUNT(course_modules.id)')
                    ->whereColumn('courses.id', 'bookmarks.course_id')
                    ->join('course_modules', 'course_modules.course_id', '=', 'courses.id'),

                'videos_count' => Course::selectRaw('COUNT(course_videos.id)')
                    ->whereColumn('courses.id', 'bookmarks.course_id')
                    ->join('course_modules', 'course_modules.course_id', '=', 'courses.id')
                    ->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id')
            ])
            ->paginate($request->per_page ?? 10);

        $bookmarkedCourses->each(function ($bookmark) {
            $bookmark->is_bookmarked = true;
        });

        if ($bookmarkedCourses->isEmpty()) {
            return $this->error([], 'Bookmark Not Found', 404);
        }

        return $this->success($bookmarkedCourses, 'Bookmark Course retrieved successfully.');
    }
}
