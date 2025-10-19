<?php

namespace App\Http\Controllers\Web\Backend;

use Carbon\Carbon;
use App\Models\Course;
use App\Models\MembershipHistory;
use App\Http\Controllers\Controller;

class RevenueController extends Controller
{
    public function index()
    {
        $courses = Course::with([
            'instructor:id,user_id',
            'instructor.user:id,first_name,last_name,role',
            'category:id,name',
            'tags:id,name',
            'courseWatches',
        ])
            ->withSum('courseWatches', 'watch_time')
            ->orderByDesc('course_watches_sum_watch_time')
            ->limit(3)
            ->get();

        $courseData = $courses->map(function ($course) {
            $totalMilliseconds = $course->courseWatches->sum('watch_time');
            $totalSeconds = floor($totalMilliseconds / 1000);

            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            $watchTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            // decimal hours
            $totalHoursDecimal = round($totalSeconds / 3600, 2);

            return [
                'name' => $course->title,
                'watch_time' => $watchTime,
                'hours_decimal' => $totalHoursDecimal,
            ];
        });

        // 1. Total revenue (owner’s share = 60%)
        $total_revenue = MembershipHistory::sum('price') * 0.60;

        // 2. This month revenue (owner’s share = 60%)
        $monthlyRevenue = MembershipHistory::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('price') * 0.60;

        // 3. Previous month revenue (owner’s share = 60%)
        $previousMonthlyRevenue = MembershipHistory::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('price') * 0.60;

        // 4. Past years revenue (before this year, owner’s 60%)
        $pastYearRevenue = MembershipHistory::whereYear('created_at', '<', Carbon::now()->year)
            ->sum('price') * 0.60;

        // 5. Past 6 months revenue (owner’s share = 60%)
        $pastSixMonthsRevenue = MembershipHistory::where('created_at', '>=', Carbon::now()->subMonths(6))
            ->sum('price') * 0.60;

        return view('backend.layouts.revenue.index', compact('courses', 'courseData', 'total_revenue', 'monthlyRevenue', 'previousMonthlyRevenue', 'pastYearRevenue', 'pastSixMonthsRevenue'));
    }
}
