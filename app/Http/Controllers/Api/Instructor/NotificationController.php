<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $data = $user->notifications()->select('id', 'data', 'read_at', 'created_at')
            ->latest()
            ->get();

        if (! $data) {
            return $this->error([], 'Notifications Not Found', 200);
        }

        return $this->success($data, 'Notifications found successfully', 200);
    }
}
