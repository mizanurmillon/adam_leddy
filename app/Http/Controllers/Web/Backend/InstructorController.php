<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function index(){
        return view('backend.layouts.instructor.index');
    }

    public function details(){
        return view('backend.layouts.instructor.details');
    }

    public function content(){
        return view('backend.layouts.instructor.content');
    }

    public function create(){
        return view('backend.layouts.instructor.create');
    }
}
