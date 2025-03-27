<?php
namespace App\Http\Controllers\Web\Backend;

use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users             = User::where('role', 'student')->paginate(10);
        $monthlyUsersCount = User::where('role', 'student')->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        return view('backend.layouts.users.index', compact('users', 'monthlyUsersCount'));
    }
}
