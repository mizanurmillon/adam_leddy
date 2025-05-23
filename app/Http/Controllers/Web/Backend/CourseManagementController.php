<?php
namespace App\Http\Controllers\Web\Backend;

use Vimeo\Vimeo;
use App\Models\Tag;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\Instructor;
use App\Models\CourseVideo;
use App\Models\CourseWatch;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseManagementController extends Controller
{
    public function index(Request $request)
    {
        $data       = $request->all();
        $categories = Category::all();

        // Start with a query to filter by category if selected
        $query = Course::with('courseWatches', 'category')->whereHas('modules', function ($query) {
                $query->whereHas('videos');
            });

        if (! empty($data['category']) && $data['category'] != 'undefined') {
            $query->where('category_id', $data['category']);
        }

        // Apply sorting at the database level
        if (! empty($data['sort']) && $data['sort'] !== "default") {
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
        $tags   = Tag::all();
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
        $watchTimesInSeconds = [];

        for ($i = 1; $i <= 12; $i++) {
            $milliseconds          = $monthlyWatchTime[$i] ?? 0;
            $totalSeconds          = floor($milliseconds / 1000);
            $watchTimesInSeconds[] = $totalSeconds;
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
            $milliseconds    = $topMonthlyWatchTime[$i] ?? 0;
            $totalSeconds    = floor($milliseconds / 1000);
            $topWatchTimes[] = $totalSeconds;
        }

        return view('backend.layouts.courses.content', compact('course', 'watchTimesInSeconds', 'tags', 'topInstructor', 'topWatchTimes'));
    }

    public function tagStore(Request $request)
    {
        $request->validate([
            'tag_id'    => 'required|exists:tags,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::find($request->course_id);

        if (! $course) {
            return response()->json(['success' => false, 'message' => 'Course not found'], 404);
        }

        if ($course->tags()->where('tag_id', $request->tag_id)->exists()) {
            $course->tags()->detach($request->tag_id);
            return response()->json(['error' => true, 'message' => 'Tag removed successfully']);
        }

        $course->tags()->attach($request->tag_id);
        return response()->json(['success' => true, 'message' => 'Tag added successfully']);
    }

    public function destroy($id)
    {
       $course = Course::find($id);

       if(file_exists($course->thumbnail)){
            unlink($course->thumbnail);
        }

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
            ]);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully.',
        ]);
    }

    public function moduleDestroy($id)
    {
       $module = CourseModule::find($id);

       if(file_exists($module->file_url)){
            unlink($module->file_url);
        }

        if (!$module) {
            return response()->json([
                'success' => false,
                'message' => 'Module not found.',
            ]);
        }

        $module->delete();

        return response()->json([
            'success' => true,
            'message' => 'Module deleted successfully.',
        ]);
    }

    public function videoDestroy($id)
    {
       $video = CourseVideo::find($id);

        if (!$video) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found.',
            ]);
        }
        
       $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));
        if (! empty($video->video_url)) {
            preg_match('/\/video\/(\d+)/', $video->video_url, $matches);
            if (! empty($matches[1])) {
                $previousVideoId = $matches[1];
                $vimeo->request("/videos/{$previousVideoId}", [], 'DELETE');
            }
        }
        $video->delete();

        return response()->json([
            'success' => true,
            'message' => 'Video deleted successfully.',
        ]);
    }
}
