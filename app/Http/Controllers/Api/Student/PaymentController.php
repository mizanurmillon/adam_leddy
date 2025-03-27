<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function checkout(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscriptions,id',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), $validateData->errors()->first(), 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User not found.', 200);
        }

        $subscriptionPlan = Subscription::find($request->plan_id);

        if (!$subscriptionPlan) {
            return $this->error([], 'Subscription plan not found.', 200);
        }

        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $subscriptionPlan->price * 100,
                        'product_data' => [
                            'name' => $subscriptionPlan->name,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'customer_email' => auth()->user()->email,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel') . '?redirect_url=' . $request->get('cancel_redirect_url'),
                'metadata' => [
                    'plan_id' => $subscriptionPlan->id,
                    'plan_name' => $subscriptionPlan->name,
                    'plan_price' => $subscriptionPlan->price,
                    'plan_type' => $subscriptionPlan->type,
                    'user_id' => (int) $user->id,
                    'success_redirect_url' => $request->get('success_redirect_url'),
                    'cancel_redirect_url' => $request->get('cancel_redirect_url'),
                ],
            ]);

            return $this->success($checkoutSession->url, 'Checkout session created successfully.', 201);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }

        return $checkoutSession;
    }

    public function checkoutSuccess(Request $request)
    {
        if (!$request->query('session_id')) {
            return $this->error([], 'Session ID not found.', 200);
        }

        try {
            $sessionId = $request->query('session_id');
            $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
            $metadata = $checkoutSession->metadata;

            return $this->success($metadata, 'Checkout success.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function checkoutCancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect($request->redirect_url ?? null);
        }

        $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
        $metadata = $checkoutSession->metadata;

        $cancel_redirect_url = $metadata->cancel_redirect_url ?? null;

        return redirect($cancel_redirect_url);
    }
}
