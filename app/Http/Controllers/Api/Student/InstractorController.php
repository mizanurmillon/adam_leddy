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
        $query = User::with(['instructor' => function ($query) {
            $query->withCount(['courses' => function ($query) {
                $query->where('status', 'approved');
            }]);
        }])
            ->where('status', 'active')
            ->where('role', 'instructor');

        if ($request->filled('name')) {
            $query->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('instructor', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        $data = $query->paginate($request->per_page ?? 30);

        if ($data->isEmpty()) {
            return $this->error([], 'Instructor Not Found', 404);
        }

        return $this->success($data, 'Instructors found successfully', 200);
    }

    public function getInstructorDetails($id)
    {
        $data = User::with(['instructor' => function ($query) {
            $query->withCount(['courses' => function ($query) {
                $query->where('status', 'approved');
            }]);
        }])
            ->where('status', 'active')
            ->where('role', 'instructor')
            ->where('id', $id)
            ->first();

        if (!$data) {
            return $this->error([], 'Instructor Not Found', 404);
        }

        return $this->success($data, 'Instructor found successfully', 200);
    }
}
