<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseWatch;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WatchListController extends Controller
{
    use ApiResponse;
    public function watchList(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'Unauthorized', 401);
        }

        $watchList = Course::query()
            ->select(['id', 'instructor_id', 'category_id', 'title', 'thumbnail'])
            ->whereHas('courseWatches', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->withCount('modules')
            ->withCount([
                'progress as progress_count' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->withCount([
                'modules as videos_count' => function ($query) {
                    $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id');
                }
            ])
            ->havingRaw('progress_count < videos_count')
            ->withExists([
                'bookmarks as is_bookmarked' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->with([
                'instructor:id,user_id',
                'instructor.user:id,first_name,last_name',
            ])
            ->orderByDesc(
                DB::table('course_watches')
                    ->select('last_watched_at')
                    ->whereColumn('course_id', 'courses.id')
                    ->where('user_id', $user->id)
                    ->latest('last_watched_at')
                    ->limit(1)
            )
            ->paginate($request->per_page ?? 10);


        // return $watchList;


        // $watchList->setCollection(
        //     $watchList->getCollection()->unique('course_id')
        // );

        return $this->success($watchList, 'Watch List', 200);
    }
}
