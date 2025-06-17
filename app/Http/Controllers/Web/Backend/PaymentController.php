<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CourseWatchHistory;
use App\Models\Instructor;
use App\Models\InstructorPayment;
use App\Models\MembershipHistory;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class PaymentController extends Controller
{
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalBalance = MembershipHistory::query()
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('price');

        $courseWatchTime = CourseWatchHistory::query()
            ->whereMonth('watched_at', $currentMonth)
            ->whereYear('watched_at', $currentYear)
            ->sum('watch_time');

        $instructors = Instructor::with([
            'user',
            'payments' => function ($query) {
                $query->latest();
            },
            'courses' => function ($query) {
                $query->select('id', 'instructor_id');
            }
        ])
            ->whereNotNull('stripe_account_id')
            ->where('status', 'Enabled')
            ->get();

        $data = [];

        foreach ($instructors as $instructor) {
            $perInstructorWatchTime = CourseWatchHistory::query()
                ->whereIn('course_id', $instructor->courses->pluck('id'))
                ->whereMonth('watched_at', $currentMonth)
                ->whereYear('watched_at', $currentYear)
                ->sum('watch_time');

            $perInstructorPercentage = $courseWatchTime > 0
                ? ($perInstructorWatchTime / $courseWatchTime) * 100
                : 0;

            $perInstructorBalance = (int) floor(($totalBalance / 100) * $perInstructorPercentage);

            $instructorEarning = $instructor->payments->sum('price');

            $lastPayment = $instructor->payments->first();
            $lastPaymentDate = optional($lastPayment)->created_at;
            $lastPaymentAmount = optional($lastPayment)->price;

            $data[] = [
                'name' => $instructor->user->first_name . ' ' . $instructor->user->last_name,
                'total_earning' => $instructorEarning,
                'last_payment_date' => $lastPaymentDate,
                'last_payment_amount' => $lastPaymentAmount,
                'due' => $perInstructorBalance
            ];
        }

        $payment_history = InstructorPayment::with('instructor.user')->get();

        return view('backend.layouts.payment.index', compact('payment_history', 'data'));
    }


    public function change()
    {
        $subscription = Subscription::first();
        return view('backend.layouts.payment.change', compact('subscription'));
    }

    public function update(Request $request)
    {
        if (User::find(auth()->user()->id)) {
            $request->validate([
                'STRIPE_PUBLIC' => 'nullable|string',
                'STRIPE_SECRET' => 'nullable|string',
            ]);
            try {
                $envContent = File::get(base_path('.env'));
                $lineBreak  = "\n";
                $envContent = preg_replace([
                    '/STRIPE_PUBLIC=(.*)\s/',
                    '/STRIPE_SECRET=(.*)\s/',
                ], [
                    'STRIPE_PUBLIC=' . $request->STRIPE_PUBLIC . $lineBreak,
                    'STRIPE_SECRET=' . $request->STRIPE_SECRET . $lineBreak,
                ], $envContent);

                if ($envContent !== null) {
                    File::put(base_path('.env'), $envContent);
                }
                return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
            } catch (\Throwable) {
                return redirect()->back()->with('t-error', 'Stripe Setting Update Failed');
            }
        }
        return redirect()->back();
    }
}
