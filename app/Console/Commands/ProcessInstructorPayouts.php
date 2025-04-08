<?php

namespace App\Console\Commands;

use App\Models\Instructor;
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

        $payouts = Instructor::whereNotNull('stripe_account_id')->get();

        if ($payouts->isEmpty()) {
            $this->info("No instructors with scheduled payouts.");
            return;
        }

        foreach ($payouts as $payout) {
            try {
                Transfer::create([
                    'amount' => 100 * 100, // Amount in cents
                    'currency' => 'usd',
                    'destination' => $payout->stripe_account_id,
                    'transfer_group' => 'COURSE_SUBSCRIPTION_' . $payout->id,
                ]);

                $payout->update(['status' => 'paid']);
                Log::info("Payout successful for instructor #{$payout->instructor_id}");
            } catch (\Exception $e) {
                $payout->update(['status' => 'failed']);
                Log::error("Payout failed for instructor #{$payout->instructor_id}: " . $e->getMessage());
            }
        }

        $this->info("Processed " . $payouts->count() . " payouts.");
    }
}
