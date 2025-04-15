<?php

namespace App\Console\Commands;

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
        Stripe::setApiKey(config('services.stripe.secret'));

        $payouts = Instructor::with('user')->whereNotNull('stripe_account_id')->where('status', 'Enabled')->get();

        if ($payouts->isEmpty()) {
            $this->info("No instructors with scheduled payouts.");
            return;
        }

        $totalBalance = MembershipHistory::query()
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('price');

        Log::info("Total Balance $totalBalance");

        $randNumber = rand(11, 99);
        foreach ($payouts as $payout) {
            try {
                $transferCreate = Transfer::create([
                    'amount' => $totalBalance * 100, // Amount in cents
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

        $this->info("Processed " . $payouts->count() . " Payouts.");
    }
}
