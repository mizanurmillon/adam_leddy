<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class InstractorController extends Controller
{
    use ApiResponse;
    public function getInstructors(Request $request)
    {
        $query = User::with(['instructor.courses'])
            ->where('status', 'active')
            ->where('role', 'instructor');

        if ($request->filled('name')) {
            $query->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%');
            });
        }

        // Eager load count of courses related to instructor
        $query->withCount('instructor.courses');

        $data = $query->get();

        if ($data->isEmpty()) {
            return $this->error([], 'Instructor Not Found', 404);
        }

        return $this->success($data, 'Instructors found successfully', 200);
    }
}
