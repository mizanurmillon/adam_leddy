<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('backend.layouts.payment.index');
    }

    public function change()
    {
        return view('backend.layouts.payment.change');
    }
}
