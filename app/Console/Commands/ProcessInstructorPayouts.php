<?php

namespace App\Console\Commands;

use App\Models\CourseWatchHistory;
use App\Models\Instructor;
use App\Models\InstructorPayment;
use App\Models\MembershipHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\Transfer;
use Illuminate\Support\Facades\Log;

class ProcessInstructorPayouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:instructor-payouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled payouts to instructors';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("Starting instructor payouts process...");
        Stripe::setApiKey(config('services.stripe.secret'));

        $payouts = Instructor::with('user')->whereNotNull('stripe_account_id')->where('status', 'Enabled')->get();

        if ($payouts->isEmpty()) {
            $this->info("No instructors with scheduled payouts.");
            return;
        }

        $lastMonth = Carbon::now()->subMonth();

        $MegaTotalBalance = MembershipHistory::query()
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('price');

        // minus 60% form MegaTotalBalance
        $totalBalance = $MegaTotalBalance - ($MegaTotalBalance * 0.6);
        Log::info("Mega Total Balance for last month: $MegaTotalBalance, After 60% deduction: $totalBalance");

        $courseWatchTime = CourseWatchHistory::query()
            ->whereMonth('watched_at', $lastMonth->month)
            ->whereYear('watched_at', $lastMonth->year)
            ->sum('watch_time');

        Log::info("Total Balance $totalBalance, Total Watch Time: $courseWatchTime");

        if ($courseWatchTime <= 0) {
            Log::warning("No course watch time recorded for the period. No payouts will be processed.");
            return;
        }

        $randNumber = rand(11, 99);
        $allInstructorWatchTime = [];
        foreach ($payouts as $payout) {
            try {

                $instructorWatchTime = CourseWatchHistory::query()
                    ->whereHas('course', function ($query) use ($payout) {
                        $query->where('instructor_id', $payout->id);
                    })
                    ->whereMonth('watched_at', $lastMonth->month)
                    ->whereYear('watched_at', $lastMonth->year)
                    ->sum('watch_time');

                $allInstructorWatchTime[$payout->id] = $instructorWatchTime;

                Log::info("Instructor ID {$payout->id} Watch Time: $instructorWatchTime");

                $peInstructorPercentage = ($instructorWatchTime / $courseWatchTime) * 100;

                Log::info("Per Instructor Watch Time Percentage: $peInstructorPercentage");

                $PerInstructorBalance = (int) floor(($totalBalance / 100) * $peInstructorPercentage);

                Log::info("Per Instructor Balance: $PerInstructorBalance");

                $transferCreate = Transfer::create([
                    'amount' => $PerInstructorBalance * 100, // Amount in cents
                    'currency' => 'usd',
                    'destination' => $payout->stripe_account_id,
                    'transfer_group' => 'COURSE_SUBSCRIPTION_' . $randNumber,
                ]);

                $transferRetrieve = Transfer::retrieve($transferCreate->id);

                Log::info($transferRetrieve);

                if ($transferRetrieve) {
                    InstructorPayment::create([
                        'instructor_id' => $payout->id,
                        'price' => $transferRetrieve->amount / 100,
                        'transaction_id' => $transferRetrieve->id,
                        'transaction_group' => $transferRetrieve->transfer_group,
                    ]);
                }

                Log::info("Payout successful for instructor(Name: {$payout->user->first_name} {$payout->user->last_name}, ID: {$payout->id})");
            } catch (\Exception $e) {
                Log::error("Payout failed for instructor(Name: {$payout->user->first_name} {$payout->user->last_name}, ID: {$payout->id}): " . $e->getMessage());
            }
        }

        Log::info("Instructors Watch Time: " . print_r($allInstructorWatchTime, true));

        $this->info("Processed " . $payouts->count() . " Payouts.");
    }

    
}
