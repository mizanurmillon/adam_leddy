<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Coupon as ModelsCoupon;
use Illuminate\Http\Request;
use Stripe\Coupon;
use Stripe\PromotionCode;
use Stripe\Stripe;

class CouponSubscriptionController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function index()
    {
        $coupons = ModelsCoupon::all();
        return view('backend.layouts.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'percent_off' => 'required|numeric',
            'valid_until' => 'nullable|date|after:today',
        ]);

        try {
            $coupon = Coupon::create([
                'id' => $request->code,
                'percent_off' => $request->percent_off,
                'duration' => 'once'
            ]);

            $promotionCode = PromotionCode::create([
                'coupon' => $coupon->id,
                'code' => $request->code,
                'expires_at' => $request->valid_until ? strtotime($request->valid_until) : null,
            ]);

            $coupon = ModelsCoupon::create([
                'code' => $request->code,
                'percent_off' => $request->percent_off,
                'duration' => 'once',
                'valid_until' => $request->valid_until,
                'is_active' => true,
                'stripe_coupon_id' => $coupon->id,
                'stripe_promotion_code_id' => $promotionCode->id,
            ]);

            // return $coupon;
            return redirect()->back()->with('t-success', 'Coupon created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', 'Error creating coupon: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $coupon = ModelsCoupon::findOrFail($id);

        if (!$coupon) {
            return redirect()->back()->with('t-error', 'Coupon not found');
        }

        try {
            // Delete the coupon from Stripe
            Coupon::retrieve($coupon->stripe_coupon_id)->delete();

            // Delete the coupon from the local database
            $coupon->delete();

            return response()->json([
                'success' => true,
                'message' => 'Coupon deleted successfully',
                'status'  => 'active'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
                'status'  => 'active',
            ]);
        }
    }

    public function status($id)
    {
        $coupon = ModelsCoupon::findOrFail($id);

        if (!$coupon) {
            return redirect()->back()->with('t-error', 'Coupon not found');
        }

        try {
            $coupon->is_active = !$coupon->is_active;
            $coupon->save();

            return response()->json([
                'success' => true,
                'message' => 'Coupon status updated successfully.',
                'status'  => 'active'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'message' => $e->getMessage(),
                'status'  => 'active',
            ]);
        }
    }
}
