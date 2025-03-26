<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WatchListController extends Controller
{
    use ApiResponse;
    public function watchList(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'Unauthorized', 401);
        }

        $watchList = $user->courseWatches()
            ->with([
                'course' => function ($query) use ($user) {
                    $query->select('id', 'instructor_id', 'category_id', 'title', 'thumbnail')
                        ->withCount('modules')
                        ->withCount(['progress as progress_count' => function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        }])
                        ->withCount(['modules as videos_count' => function ($query) {
                            $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id');
                        }])
                        ->withExists(['bookmarks as is_bookmarked' => function ($query) use ($user) {
                            $query->where('user_id', $user->id);
                        }]);
                },
                'course.instructor:id,user_id,category_id',
                'course.instructor.user:id,first_name,last_name',

            ])
            ->latest('last_watched_at')
            ->paginate($request->per_page ?? 10);

        $watchList->setCollection(
            $watchList->getCollection()->unique('course_id')
        );

        return $this->success($watchList, 'Watch List', 200);
    }
}
