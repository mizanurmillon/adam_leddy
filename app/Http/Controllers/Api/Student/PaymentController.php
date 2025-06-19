<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Membership;
use App\Models\MembershipHistory;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Subscription as StripeSubscription;

class PaymentController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function checkout(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscriptions,id',
            'discount_code' => 'nullable|string|max:255',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), $validateData->errors()->first(), 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User not found.', 200);
        }

        $subscriptionPlan = Subscription::find($request->plan_id);

        if (!$subscriptionPlan || !$subscriptionPlan->stripe_price_id) {
            return $this->error([], 'Subscription plan or Stripe price ID not found.', 200);
        }

        $CouponCode = null;
        if ($request->discount_code) {
            $coupon = Coupon::where('code', $request->discount_code)
                ->where('is_active', true)
                ->first();

            if ($coupon) {
                $CouponCode = $coupon->stripe_promotion_code_id;
            }
        }

        try {
            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'subscription',
                'line_items' => [[
                    'price' => $subscriptionPlan->stripe_price_id,
                    'quantity' => 1,
                ]],
                'discounts' => [[
                    'promotion_code' => $CouponCode,
                ]],
                'customer_email' => $user->email,
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel') . '?redirect_url=' . $request->get('cancel_redirect_url'),
                'metadata' => [
                    'user_id' => $user->id,
                    'subscription_id' => $subscriptionPlan->id,
                    'success_redirect_url' => $request->get('success_redirect_url'),
                    'cancel_redirect_url' => $request->get('cancel_redirect_url'),
                ],
            ]);

            return $this->success($checkoutSession->url, 'Checkout session created successfully.', 201);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function checkoutSuccess(Request $request)
    {
        if (!$request->query('session_id')) {
            return $this->error([], 'Session ID not found.', 200);
        }

        DB::beginTransaction();
        try {
            $sessionId = $request->query('session_id');
            $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
            $subscriptionId = $checkoutSession->subscription;

            $stripeSubscription = \Stripe\Subscription::retrieve($subscriptionId);
            $priceId = $stripeSubscription->items->data[0]->price->id;
            $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);

            $metadata = $checkoutSession->metadata;

            $user = User::find($metadata->user_id);
            $subscriptionPlan = Subscription::find($metadata->subscription_id);
            $success_redirect_url = $metadata->success_redirect_url ?? '/';

            if (!$user || !$subscriptionPlan) {
                return $this->error([], 'User or Plan not found.', 200);
            }

            // Save student stripe subscription info
            $user->student()->updateOrCreate([], [
                'stripe_customer_id' => $checkoutSession->customer,
                'stripe_subscription_id' => $subscriptionId,
            ]);

            // Save active membership
            $user->membership()->updateOrCreate([
                'subscription_id' => $subscriptionPlan->id,
            ], [
                'price' => $subscriptionPlan->price,
                'type' => $subscriptionPlan->type,
                'stripe_subscription_id' => $subscriptionId,
                'start_date' => now(),
                'end_date' => $currentPeriodEnd,
                'starts_at' => now(),
            ]);

            // Save to history
            $user->membershipHistories()->create([
                'subscription_id' => $subscriptionPlan->id,
                'price' => $subscriptionPlan->price,
                'type' => $subscriptionPlan->type,
                'stripe_subscription_id' => $subscriptionId,
                'start_date' => now(),
                'end_date' => $currentPeriodEnd,
                'starts_at' => now(),
            ]);

            DB::commit();
            return redirect($success_redirect_url);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function checkoutCancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect($request->redirect_url ?? '/');
        }

        $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
        $metadata = $checkoutSession->metadata;
        $cancel_redirect_url = $metadata->cancel_redirect_url ?? '/';

        return redirect($cancel_redirect_url);
    }

    public function AutoRenewWebhook(Request $request)
    {
        $payload = $request->all();

        $subscriptionId = $payload['data']['object']['subscription'];
        $stripeCustomerId = $payload['data']['object']['customer'];

        $student = Student::where('stripe_customer_id', $stripeCustomerId)->first();
        Log::info('AutoRenewWebhook called for student ID: ' . $student->id);

        if (!$student) return response()->json(['message' => 'Student not found'], 404);
        Log::info('Found student: ' . $student->id);

        $membership = $student->user->membership()->where('stripe_subscription_id', $subscriptionId)->first();
        Log::info('Found membership: ' . ($membership ? $membership->id : 'none'));

        if (!$membership) return response()->json(['message' => 'Membership not found'], 404);


        $stripeSubscription = \Stripe\Subscription::retrieve($subscriptionId);
        $newEndDate = Carbon::createFromTimestamp($stripeSubscription->current_period_end);

        $membership->update(['end_date' => $newEndDate]);

        MembershipHistory::create([
            'user_id' => $student->user_id,
            'subscription_id' => $membership->subscription_id,
            'price' => $membership->price,
            'type' => $membership->type,
            'stripe_subscription_id' => $subscriptionId,
            'start_date' => now(),
            'end_date' => $newEndDate,
        ]);

        return response()->json(['message' => 'Membership renewed.'], 200);
    }

    public function subscriptionCancel(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return $this->error([], 'User not found.', 401);
        }

        $membership = Membership::where('user_id', $user->id)->first();

        if (!$membership || !$membership->stripe_subscription_id) {
            return $this->error([], 'Active subscription not found.', 404);
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $subscription = StripeSubscription::retrieve($membership->stripe_subscription_id);
            $subscription->cancel();

            // Optionally update your DB to mark it pending cancel
            $user->membership()->where('stripe_subscription_id', $membership->stripe_subscription_id)->update([
                'status' => 'cancelled',
            ]);

            return $this->success([], 'Subscription will be cancelled at period end.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
