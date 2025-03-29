<?php

namespace App\Http\Controllers\Api\Instructor;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\InstructorPayment;
use App\Http\Controllers\Controller;

class EarningsController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        $instructor = auth()->user()->instructor;

        if (! $instructor) {
            return $this->error([], 'Instructor Not Found', 404);
        }

        $data = InstructorPayment::where('instructor_id', $instructor->id)->get();

        if(! $data) {
            return $this->error([], 'Payments history Not Found', 200);
        }

        return $this->success($data, 'Payments history found successfully', 200);
    }
}
