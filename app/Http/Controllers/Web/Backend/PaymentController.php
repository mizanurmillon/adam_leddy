<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\InstructorPayment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class PaymentController extends Controller
{
    public function index()
    {
        $payment_history = InstructorPayment::with('instructor.user')->get();
        return view('backend.layouts.payment.index', compact('payment_history'));
    }

    public function change()
    {
        return view('backend.layouts.payment.change');
    }

    public function update(Request $request)
    {
        if (User::find(auth()->user()->id)) {
            $request->validate([
                'STRIPE_PUBLIC' => 'nullable|string',
                'STRIPE_SECRET' => 'nullable|string',
            ]);
            try {
                $envContent = File::get(base_path('.env'));
                $lineBreak  = "\n";
                $envContent = preg_replace([
                    '/STRIPE_PUBLIC=(.*)\s/',
                    '/STRIPE_SECRET=(.*)\s/',
                ], [
                    'STRIPE_PUBLIC=' . $request->STRIPE_PUBLIC . $lineBreak,
                    'STRIPE_SECRET=' . $request->STRIPE_SECRET . $lineBreak,
                ], $envContent);

                if ($envContent !== null) {
                    File::put(base_path('.env'), $envContent);
                }
                return redirect()->back()->with('t-success', 'Stripe Setting Update successfully.');
            } catch (\Throwable) {
                return redirect()->back()->with('t-error', 'Stripe Setting Update Failed');
            }
        }
        return redirect()->back();
    }
}
