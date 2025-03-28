<?php
namespace App\Http\Controllers\Web\Backend;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\MembershipHistory;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $monthlyUsersCount = User::where('role', 'student')->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $totalUsers = User::where('role', 'student')->count();

        $monthlyInstructorCount = User::where('role', 'instructor')->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        $totalInstructor = User::where('role', 'instructor')->count();

        $total_course = Course::count();
        $total_category = Category::count();
        $total_tag = Tag::count();

        $total_revenue = MembershipHistory::sum('price');

        $monthlyRevenue = MembershipHistory::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('price');

        return view('backend.layouts.index', compact('monthlyUsersCount', 'totalUsers', 'monthlyInstructorCount', 'totalInstructor', 'total_course', 'total_category', 'total_tag', 'total_revenue', 'monthlyRevenue'));
    }
}
