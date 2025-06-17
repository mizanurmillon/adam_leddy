<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Product;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stripe_product_id' => 'nullable|string',
        ]);

        try {
            $subscription = Subscription::first();

            if (!$subscription) {
                return redirect()->back()->with('t-error', 'Subscription not found');
            }

            Stripe::setApiKey(config('services.stripe.secret'));

            if ($request->stripe_product_id) {
                $product = Product::retrieve($request->stripe_product_id);
                $priceId = $product->default_price;
            } else {
                $priceId = $subscription->stripe_price_id;
            }

            // Update your local DB
            $subscription->update([
                'name' => $request->name,
                'price' => $request->price,
                'stripe_price_id' => $priceId,
            ]);

            return redirect()->back()->with('t-success', 'Subscription Details Successfully Updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Error updating subscription: ' . $e->getMessage());
        }
    }
}
