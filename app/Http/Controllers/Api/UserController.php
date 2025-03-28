<?php

namespace App\Http\Controllers\Api;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Fetch Login User Data
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function getUserData()
    {

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Not Found', 200);
        }

        $user->setAttribute('is_premium', $user->isMember() && !$user->isExpired() ? 1 : 0);
        unset($user->membership);

        if ($user->role == 'instructor') {
            $user->load('instructor');
        }
        return $this->success($user, 'User data fetched successfully', 200);
    }

    public function updateUserData(Request $request)
    {

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Not Found', 200);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();

        return $this->success($user, 'User data updated successfully', 200);
    }

    public function updateUserImage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Not Found', 200);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $previousImagePath = public_path($user->avatar);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }
            $avatar     = $request->file('avatar');
            $avatarName = uploadImage($avatar, 'users');
        } else {
            $avatarName = $user->avatar;
        }

        $user->avatar = $avatarName;
        $user->save();

        return $this->success($user, 'User image updated successfully', 200);
    }

    public function userLogout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success([], 'Successfully logged out', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'password.min' => 'The password must be at least 8 characters long.',
        ]);

        if ($validator->fails()) {
            return $this->error([], $validator->errors(), 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User Not Found', 200);
        }

        // Check if the current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error([], "Current password is incorrect", 400);
        }

        // Update the password securely
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->success($user, 'Password changed successfully', 200);
    }
}
