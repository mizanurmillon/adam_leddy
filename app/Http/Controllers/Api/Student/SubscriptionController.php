<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ApiResponse;
    public function subscriptionPlans()
    {
        $data = Subscription::all();

        if ($data->isEmpty()) {
            return $this->error([], 'Subscription Plans Not Found', 200);
        }

        return $this->success($data, 'Subscription Plans found successfully', 200);
    }
}
