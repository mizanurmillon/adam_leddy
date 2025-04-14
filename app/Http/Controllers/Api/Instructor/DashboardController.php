<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\InstructorPayment;
use App\Traits\ApiResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $instructor = $user->instructor;

        $courses = Course::with('courseWatches')->where('instructor_id', $instructor->id)->get();

        // dd($courses);

        $total_earnings    = InstructorPayment::where('instructor_id', $instructor->id)->sum('price');
        $thisMonthEarnings = InstructorPayment::where('instructor_id', $instructor->id)->whereMonth('created_at', date('m'))->sum('price');
        $previousMonthEarnings = InstructorPayment::where('instructor_id', $instructor->id)->whereMonth('created_at', date('m', strtotime('-1 month')))->sum('price');

        $total_watch_milliseconds = $courses->pluck('courseWatches')->flatten()->sum('watch_time');
        $total_watch_seconds = floor($total_watch_milliseconds / 1000); // convert to seconds

        $hours = floor($total_watch_seconds / 3600);
        $minutes = floor(($total_watch_seconds % 3600) / 60);

        $total_watch_time_formatted = "{$hours}h {$minutes}m";

        $thisMonthWatches = $courses->pluck('courseWatches')
            ->flatten()
            ->filter(function ($watch) {
                return \Carbon\Carbon::parse($watch->created_at)->month == date('m');
            })
            ->sum('watch_time');
        $thisMonthSeconds = floor($thisMonthWatches / 1000); // ✅ Convert ms to sec
        $thisMonthHours   = floor($thisMonthSeconds / 3600);
        $thisMonthMinutes = floor(($thisMonthSeconds % 3600) / 60);

        $thisMonthFormatted = "{$thisMonthHours}h {$thisMonthMinutes}m";

        $earnings = [
            'this_month_earnings' => $thisMonthEarnings,
            'total_earnings'      => $total_earnings,
            'previous_month_earnings' => $previousMonthEarnings
        ];

        $watches = [
            'this_month_watches' => $thisMonthFormatted,
            'total_watches'      => $total_watch_time_formatted,
        ];

        $data = [
            'earnings' => $earnings,
            'watches'  => $watches,
        ];
        return $this->success($data, 'Dashboard Data', 200);
    }
}
