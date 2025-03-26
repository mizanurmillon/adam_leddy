<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        return view('backend.layouts.approval.index');
    }

    public function content()
    {
        return view('backend.layouts.approval.content');
    }

    public function view()
    {
        return view('backend.layouts.approval.view');
    }
}
