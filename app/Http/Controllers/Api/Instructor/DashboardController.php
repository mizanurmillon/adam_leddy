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

        $total_watch_seconds = $courses->pluck('courseWatches')->flatten()->sum('watch_time');
        $hours               = floor($total_watch_seconds / 3600);
        $minutes             = floor(($total_watch_seconds % 3600) / 60);

        $thisMonthWatches = $courses->pluck('courseWatches')
            ->flatten()
            ->filter(function ($watch) {
                return \Carbon\Carbon::parse($watch->created_at)->month == date('m');
            })
            ->sum('watch_time');
        $thisMonthHours   = floor($thisMonthWatches / 3600);
        $thisMonthMinutes = floor(($thisMonthWatches % 3600) / 60);

        $earnings = [
            'this_month_earnings' => $thisMonthEarnings,
            'total_earnings'      => $total_earnings,
            'previous_month_earnings' => $previousMonthEarnings
        ];

        $watches = [
            'this_month_watches' => "{$thisMonthHours}h {$thisMonthMinutes}m",
            'total_watches'      => "{$hours}h {$minutes}m",
        ];

        $data = [
            'earnings' => $earnings,
            'watches'  => $watches,
        ];
        return $this->success($data, 'Dashboard Data', 200);
    }
}
