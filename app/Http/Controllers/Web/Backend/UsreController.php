<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsreController extends Controller
{
    public function index()
    {
        return view('backend.layouts.users.index');
    }
}
