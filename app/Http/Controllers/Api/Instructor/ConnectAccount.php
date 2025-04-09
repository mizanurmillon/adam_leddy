<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Traits\ApiResponse;
use Stripe\Account;
use Stripe\AccountLink;

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

        if (!$instructor) {
            return $this->error([], 'Instructor not found.', 200);
        }

        $userStripeAccountId = $instructor ? $instructor->stripe_account_id : null;

        if (!$userStripeAccountId) {
            $account = $stripe->accounts->create([
                'type' => 'express',
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
            ]);

            $instructor->update(['stripe_account_id' => $account->id]);
        } else {
            $account = $stripe->accounts->retrieve($userStripeAccountId);
        }

        // return $account;

        if ($instructor->status == 'Enabled') {
            return $this->error([], 'Your account is already connected.', 200);
        }
        if ($account && $account->payouts_enabled == true) {
            return $this->error([], 'Your account is already connected.', 200);
        }

        $accountLink = $stripe->accountLinks->create([
            'account' => $account->id,
            'refresh_url' => route('connect.cancel') . "?id=" . $account->id . "&userId=" . $instructor->id . "&success_redirect_url=" . $request->success_redirect_url . "&cancel_redirect_url=" . $request->cancel_redirect_url,
            'return_url' => route('connect.success') . "?id=" . $account->id . "&userId=" . $instructor->id . "&success_redirect_url=" . $request->success_redirect_url . "&cancel_redirect_url=" . $request->cancel_redirect_url,
            'type' => 'account_onboarding',
        ]);

        return response()->json(['url' => $accountLink]);
    }

    public function success(Request $request)
    {
        $account = Account::retrieve($request->id);

        $instructor = Instructor::find($request->get('userId'));

        if (!$instructor) {
            return $this->error([], 'Instructor not found.', 200);
        }

        if (!$account->details_submitted || !$account->payouts_enabled) {
            $instructor->update([
                'status' => 'Rejected'
            ]);
            return redirect()->away($request->get('cancel_redirect_url'));
        }

        $instructor->update([
            'status' => 'Enabled'
        ]);

        return redirect($request->get('success_redirect_url'));
    }

    public function cancel(Request $request)
    {
        $link = AccountLink::create([
            'account' => $request->id,
            'refresh_url' => route('connect.cancel') . "?id=" . $request->id . "&userId=" . $request->userId . "&success_redirect_url=" . $request->success_redirect_url . "&cancel_redirect_url=" . $request->cancel_redirect_url,
            'return_url' => route('connect.success') . "?id=" . $request->id . "&userId=" . $request->userId . "&success_redirect_url=" . $request->success_redirect_url . "&cancel_redirect_url=" . $request->cancel_redirect_url,
            'type' => 'account_onboarding',
        ]);
        return redirect($link->url);
    }
}
