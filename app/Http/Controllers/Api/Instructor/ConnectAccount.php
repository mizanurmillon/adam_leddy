<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Traits\ApiResponse;

class ConnectAccount extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function connectAccount(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $instructor = $request->user()->instructor;

        $userStripeAccountId = $instructor ? $instructor->stripe_account_id : null;

        if (!$userStripeAccountId) {
            $account = $stripe->accounts->create(['type' => 'standard']);

            $instructor->update(['stripe_account_id' => $account->id]);
        } else {
            $account = $stripe->accounts->retrieve($userStripeAccountId);
        }

        $accountLink = $stripe->accountLinks->create([
            'account' => $account->id,
            'refresh_url' => route('connect.success'),
            'return_url' => route('connect.success'),
            'type' => 'account_onboarding',
        ]);

        return response()->json(['url' => $accountLink]);
    }


    public function success(Request $request)
    {
        // Logic for successful connection
        return response()->json(['message' => 'Connection successful']);
    }

    public function cancel(Request $request)
    {
        // Logic for cancelled connection
        return response()->json(['message' => 'Connection cancelled']);
    }
}
