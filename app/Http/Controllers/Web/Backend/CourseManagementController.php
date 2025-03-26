<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseManagementController extends Controller
{
    public function index()
    {
        return view('backend.layouts.courses.index');
    }

    public function content()
    {
        return view('backend.layouts.courses.content');
    }
}
