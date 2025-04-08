<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseProgress;
use App\Models\CourseWatch;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseWatchTimeAndProgressController extends Controller
{
    use ApiResponse;
    public function watch(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|integer|exists:courses,id',
            'course_module_id' => 'required|integer|exists:course_modules,id',
            'course_video_id' => 'required|integer|exists:course_videos,id',
            'watch_time' => 'required|integer|min:1',
            'finished' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        DB::beginTransaction();
        try {
            if ($request->filled('finished')) {
                $courseProgress = CourseProgress::where('user_id', $user->id)
                    ->where('course_id', $request->course_id)
                    ->where('course_module_id', $request->course_module_id)
                    ->where('course_video_id', $request->course_video_id)
                    ->first();

                if (!$courseProgress) {
                    $courseProgress = CourseProgress::create([
                        'user_id' => $user->id,
                        'course_id' => $request->course_id,
                        'course_module_id' => $request->course_module_id,
                        'course_video_id' => $request->course_video_id,
                        'finished_at' => Carbon::now()
                    ]);
                }
                $data = $courseProgress;
                $message = 'Watch time updated and course marked as finished';
            } else {
                $courseWatch = CourseWatch::where('user_id', $user->id)
                    ->where('course_id', $request->course_id)
                    ->where('course_module_id', $request->course_module_id)
                    ->where('course_video_id', $request->course_video_id)
                    ->whereMonth('last_watched_at', Carbon::now()->month)
                    ->whereYear('last_watched_at', Carbon::now()->year)
                    ->first();

                if ($courseWatch) {
                    // Increment watch time
                    $courseWatch->watch_time += $request->watch_time;
                    $courseWatch->last_watched_at = Carbon::now();
                } else {
                    // Create a new course watch record
                    $courseWatch = new CourseWatch();

                    $courseWatch->user_id = $user->id;
                    $courseWatch->course_id = $request->course_id;
                    $courseWatch->course_module_id = $request->course_module_id;
                    $courseWatch->course_video_id = $request->course_video_id;
                    $courseWatch->watch_time = $request->watch_time;
                    $courseWatch->last_watched_at = Carbon::now();
                }

                $courseWatch->save();

                $data = $courseWatch;

                $message = 'Watch time updated';
            }
            DB::commit();
            return $this->success($data, $message, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 'Something went wrong', 500);
        }
    }
}
