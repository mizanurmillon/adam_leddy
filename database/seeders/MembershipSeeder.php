<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $userId = 2; // Use only user ID 2
        $subscriptions = DB::table('subscriptions')->pluck('id');

        if ($subscriptions->isEmpty()) {
            return; // Exit if no subscriptions exist
        }

        $subscriptionId = $subscriptions->random();
        $price = 8; // Fixed price of 8
        $type = 'monthly';

        // Insert 12 rows into membership_histories with different start and end dates
        for ($i = 0; $i < 12; $i++) {
            $startDate = Carbon::now()->subMonths($i);
            $endDate = $startDate->copy()->addMonth();

            DB::table('membership_histories')->insert([
                'user_id' => $userId,
                'subscription_id' => $subscriptionId,
                'price' => $price,
                'type' => $type,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Check if user already has a membership
        $existingMembership = DB::table('memberships')->where('user_id', $userId)->first();
        $latestStartDate = Carbon::now();
        $latestEndDate = $latestStartDate->copy()->addMonth();

        if ($existingMembership) {
            // Update existing membership
            DB::table('memberships')->where('user_id', $userId)->update([
                'subscription_id' => $subscriptionId,
                'price' => $price,
                'type' => $type,
                'start_date' => $latestStartDate,
                'end_date' => $latestEndDate,
                'updated_at' => now(),
            ]);
        } else {
            // Insert new membership (Single Active Record)
            DB::table('memberships')->insert([
                'user_id' => $userId,
                'subscription_id' => $subscriptionId,
                'price' => $price,
                'type' => $type,
                'start_date' => $latestStartDate,
                'end_date' => $latestEndDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
