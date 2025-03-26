<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use App\Models\Category;
use App\Mail\InstructorMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class InstructorController extends Controller
{
    public function index(){
        $instructors = User::with('instructor.courses.courseWatches')->where('role', 'instructor')->paginate(10);
        return view('backend.layouts.instructor.index', compact('instructors'));
    }

    public function details(){
        return view('backend.layouts.instructor.details');
    }

    public function content(){
        return view('backend.layouts.instructor.content');
    }

    public function create(){

        $categories = Category::all();
        return view('backend.layouts.instructor.create', compact('categories'));
    }

    public function store(Request $request){
        $request->validate([
            'first_name'       => 'required|string|max:255',
            'last_name'        => 'nullable|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:8',
            'category_id'      => 'required|exists:categories,id',
            'expertise'        => 'required|string|max:255',
            'bio'              => 'nullable|string|max:2000',
        ]);

        try {
            DB::beginTransaction();
            $password = $request->password;
            $password_hash = Hash::make($password);

            $user = User::create([
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'email'            => $request->email,
                'password'         => $password_hash,
                'role'             => 'instructor',
                'email_verified_at' => now(),
            ]);

            $user->instructor()->create([
                'category_id'      => $request->category_id,
                'expertise'        => $request->expertise,
                'bio'              => $request->bio,
            ]);

            $data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => $password,
            ];

            Mail::to($user->email)->send(new InstructorMail($data));

            DB::commit();

            return redirect()->route('admin.instructors.index')->with('t-success', 'Instructor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.instructors.index')->with('t-error', $e->getMessage());
        }
    }
}
