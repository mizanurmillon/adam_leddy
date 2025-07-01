<?php
namespace App\Http\Controllers\Web\Backend;

use App\Enum\NotificationType;
use App\Http\Controllers\Controller;
use App\Mail\InstructorMail;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseWatch;
use App\Models\Instructor;
use App\Models\User;
use App\Notifications\UserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class InstructorController extends Controller
{
    public function index()
    {
        $instructors       = User::where('role', 'instructor','instructor.courses.courseWatches')->paginate(10);
        $monthlyUsersCount = User::where('role', 'instructor')->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        return view('backend.layouts.instructor.index', compact('instructors', 'monthlyUsersCount'));
    }

    public function details($id)
    {
        $instructor = Instructor::with('user')->where('id', $id)->first();

        $query = Course::with([
            'instructor:id,user_id',
            'instructor.user:id,first_name,last_name,role',
            'category:id,name',
            'tags:id,name',
            'courseWatches',
        ])
            ->withCount(['modules', 'modules as videos_count' => function ($query) {
                $query->join('course_videos', 'course_videos.course_module_id', '=', 'course_modules.id')
                    ->select(DB::raw('count(course_videos.id)'));
            }])
            ->where('instructor_id', $id)
            ->whereHas('modules', function ($query) {
                $query->whereHas('videos');
            });

        $courses = $query->get();

        // Watch time per month calculation
        $watchTimeData = CourseWatch::whereIn('course_id', $courses->pluck('id'))
            ->selectRaw('MONTH(last_watched_at) as month, SUM(watch_time) as total_time')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Convert data for JS chart
        $months    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $watchData = array_fill(0, 12, 0);

        foreach ($watchTimeData as $data) {
            $watchData[$data->month - 1] = $data->total_time; // Mapping month index
        }

        return view('backend.layouts.instructor.details', compact('instructor', 'courses', 'months', 'watchData'));
    }

    public function content($id)
    {
        $course = Course::with('instructor.user', 'modules.videos', 'modules.courseWatches')
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
            ->withSum('courseWatches', 'watch_time')
            ->orderByDesc('course_watches_sum_watch_time')
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

        return view('backend.layouts.instructor.content', compact('course', 'watchTimesInSeconds', 'topInstructor', 'topWatchTimes'));
    }

    public function create()
    {

        $categories = Category::all();
        return view('backend.layouts.instructor.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6',
            'bio'        => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();
            $password      = $request->password;
            $password_hash = Hash::make($password);

            $user = User::create([
                'first_name'        => $request->first_name,
                'last_name'         => $request->last_name,
                'email'             => $request->email,
                'password'          => $password_hash,
                'role'              => 'instructor',
                'email_verified_at' => now(),
            ]);

            $user->instructor()->create([
                'bio' => $request->bio,
            ]);

            $data = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'password'   => $password,
            ];

            Mail::to($user->email)->send(new InstructorMail($data));

            DB::commit();

            return redirect()->route('admin.instructors.index')->with('t-success', 'Instructor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.instructors.index')->with('t-error', $e->getMessage());
        }
    }

    public function status($id)
    {
        $data = Instructor::with('user')->find($id);

        if ($data->user->status == 'active') {

            $data->user->status = 'inactive';
            $data->user->save();

            // Notify the user
            $data->user->notify(new UserNotification(
                message: 'Your account has been blocked.',
                channels: ['database'],
                type: NotificationType::ERROR,
            ));

            return response()->json([
                'success' => true,
                'message' => 'Instructor blocked successfully.',
                'status'  => 'inactive',
                'data'    => $data,
            ]);

        } else {

            $data->user->status = 'active';
            $data->user->save();

            // Notify the user
            $data->user->notify(new UserNotification(
                message: 'Your account has been activated.',
                channels: ['database'],
                type: NotificationType::SUCCESS,
            ));

            return response()->json([
                'success' => true,
                'message' => 'Instructor published successfully.',
                'status'  => 'active',
                'data'    => $data,
            ]);
        }
    }

    public function destroy($id)
    {
        $instructor = Instructor::find($id);

        if (! $instructor) {
            return response()->json([
                'success' => false,
                'message' => 'Instructor not found.',
            ]);
        }

        $instructor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Instructor deleted successfully.',
        ]);
    }
}
