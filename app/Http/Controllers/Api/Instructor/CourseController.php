<?php

namespace App\Http\Controllers\Api\Instructor;

use Vimeo\Vimeo;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseTag;
use App\Models\CourseVideo;
use App\Traits\ApiResponse;
use App\Models\CourseModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    use ApiResponse;

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 200);
        }

        $data = User::with('instructor')->where('role', 'instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 200);
        }
        $thumbnailName = $request->hasFile('thumbnail')
            ? uploadImage($request->file('thumbnail'), 'course')
            : null;

        $course = Course::create([
            'instructor_id' => $data->instructor->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'thumbnail'     => $thumbnailName,
        ]);
        // $tag = CourseTag::create([
        //     'course_id' => $course->id,
        //     'tag_id'    => $request->tag_id
        // ]);
        $course->load('category');
        return $this->success($course, 'Course created successfully', 200);
    }

    public function createModule(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string|max:255',
            'video_url' => 'required|mimes:mp4,mov,ogg,qt,ogx,mkv,wmv,webm,flv,avi,ogv|max:512000',
            'file_url'  => 'nullable|mimes:pdf,doc,docx|max:4096',
            'video_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 200);
        }

        $fileName = $request->hasFile('file_url')
            ? uploadImage($request->file('file_url'), 'course')
            : null;

        $course = Course::findOrFail($id);

        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));

        // Ensure the file is valid
        $videoFile = $request->file('video_url');

        if (! $videoFile->isValid()) {
            return $this->error([], "Invalid video file", 422);
        }

        // Allowed Mime Types
        $allowedMimeTypes = ['video/mp4', 'video/quicktime', 'video/x-ms-wmv', 'video/x-msvideo'];
        if (! in_array($videoFile->getMimeType(), $allowedMimeTypes)) {
            return $this->error([], "Unsupported video format", 422);
        }

        // Upload video to Vimeo
        try {
            $courseVideoResponse = $vimeo->upload($videoFile->getPathname(), [
                'name'        => $course->title,
                'description' => $course->description,
                'privacy'     => [
                    'view' => 'anybody',
                ],
                'embed'       => [
                    'title'   => [
                        'name'     => 'hide',
                        'owner'    => 'hide',
                        'portrait' => 'hide',
                    ],
                    'buttons' => [
                        'like'       => false,
                        'watchlater' => false,
                        'share'      => false,
                        'embed'      => false,
                    ],
                    'logos'   => [
                        'vimeo' => false,
                    ],
                ],
            ]);

            $courseVideoData = $vimeo->request($courseVideoResponse, [], 'GET')['body'];
            $courseVideoId   = trim($courseVideoData['uri'], '/videos/');
            $videoEmbedUrl   = "https://player.vimeo.com/video/" . $courseVideoId . "?title=1&byline=1&portrait=1&badge=1&autopause=1&player_id=1";

            // Get video duration with retries
            $courseVideoDuration = 0;
            $retryCount          = 0;

            while ($courseVideoDuration == 0 && $retryCount < 5) {
                sleep(5); // Wait for the video to process
                $courseVideoData     = $vimeo->request($courseVideoResponse, [], 'GET')['body'];
                $courseVideoDuration = $courseVideoData['duration'];
                $retryCount++;
            }

            if ($courseVideoDuration > 0) {
                $formattedDuration = gmdate("H:i:s", $courseVideoDuration);
            } else {
                return $this->error([], "Video duration retrieval failed", 422);
            }
        } catch (\Exception $e) {
            return $this->error([], "Video upload failed: " . $e->getMessage(), 500);
        }

        // Create Course Module
        $module = CourseModule::create([
            'course_id'    => $course->id,
            'module_title' => $request->title,
            'file_url'         => $fileName,
            'description' => $request->description,
        ]);

        // Create Video Entry
        CourseVideo::create([
            'course_module_id' => $module->id,
            'video_title'      => $request->video_title,
            'video_url'        => $videoEmbedUrl, // Store as string
            'duration'         => $formattedDuration,
        ]);

        $module->load('videos');

        return $this->success($module, 'Module created successfully', 201);
    }

    public function getCourse(Request $request)
    {
        $user = auth()->user();

        $data = User::with('instructor',)->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 200);
        }

        $course = Course::with('instructor.user:id,first_name,last_name,role', 'category')->where('instructor_id', $data->instructor->id);

        if ($request->status == 'pending') {
            $course->where('status', 'pending');
        }

        if ($request->status == 'drafts') {
            $course->where('status', 'drafts');
        }

        if ($request->status == 'approved') {
            $course->where('status', 'approved');
        }

        if ($request->status == 'rejected') {
            $course->where('status', 'rejected');
        }
        $courses = $course->get();

        // Add total watch time to each course
        $courses->map(function ($course) {
            $totalMilliseconds = $course->courseWatches->sum('watch_time');
            $totalSeconds = floor($totalMilliseconds / 1000); // Convert ms to s

            $course->total_watch_times = gmdate('H:i:s', $totalSeconds);
            unset($course->courseWatches);

            return $course;
        });

        if ($courses->isEmpty()) {
            return $this->error([], 'Course Not Found', 200);
        }

        return $this->success($courses, 'Course found successfully', 200);
    }

    public function getCourseDetails($id)
    {
        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 200);
        }

        $course = Course::with('instructor.user:id,first_name,last_name,role', 'category', 'tags', 'modules.videos')->where('instructor_id', $data->instructor->id)->where('id', $id)->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        return $this->success($course, 'Course found successfully', 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 200);
        }

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 200);
        }

        $course = Course::where('instructor_id', $data->instructor->id)->where('id', $id)->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        // Thumbnail Upload
        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) {
                $previousImagePath = public_path($course->thumbnail);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }
            $thumbnail     = $request->file('thumbnail');
            $thumbnailName = uploadImage($thumbnail, 'course');
        } else {
            $thumbnailName = $course->thumbnail;
        }

        // Course Update
        $course->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'thumbnail'   => $thumbnailName,
        ]);

        $course->load('category');
        return $this->success($course, 'Course updated successfully', 200);
    }

    public function updateModule(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title'     => 'nullable|string|max:255',
            'video_url' => 'nullable|mimes:mp4,mov,ogg,qt,ogx,mkv,wmv,webm,flv,avi,ogv|max:512000',
            'file_url'  => 'nullable|mimes:pdf,doc,docx|max:4096',
            'video_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();
        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 200);
        }

        $Course_module = CourseModule::find($id);
        if (! $Course_module) {
            return $this->error([], 'Module Not Found', 200);
        }

        // Handle File Upload
        $fileName = $Course_module->file_url ?? null;
        if ($request->hasFile('file_url')) {
            if ($fileName) {
                $previousFilePath = public_path($fileName);
                if (file_exists($previousFilePath)) {
                    unlink($previousFilePath);
                }
            }
            $file     = $request->file('file_url');
            $fileName = uploadImage($file, 'course');
        } else {
            $fileName = $Course_module->file_url;
        }

        // Update Course Module
        $Course_module->update([
            'module_title' => $request->title,
            'file_url'  => $fileName,
            'description' => $request->description,
        ]);

        return $this->success($Course_module, 'Module updated successfully', 200);
    }

    public function delete($id)
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User Not Found', 200);
        }

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to delete courses', 200);
        }

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $data || ! $data->instructor) {
            return $this->error([], 'Instructor Not Found', 200);
        }

        $course = Course::with('modules.videos')->where('instructor_id', $data->instructor->id)->where('id', $id)->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));

        // Delete all videos from Vimeo
        foreach ($course->modules as $module) {
            foreach ($module->videos as $video) {
                if (! empty($video->video_url)) {
                    preg_match('/\/video\/(\d+)/', $video->video_url, $matches);
                    if (! empty($matches[1])) {
                        $previousVideoId = $matches[1];
                        $vimeo->request("/videos/{$previousVideoId}", [], 'DELETE');
                    }
                }

                // Delete local video file
                if (! empty($video->file_url)) {
                    $previousFilePath = public_path($video->file_url);
                    if (file_exists($previousFilePath)) {
                        unlink($previousFilePath);
                    }
                }
            }
        }

        // Delete course thumbnail if exists
        if (! empty($course->thumbnail)) {
            $previousThumbnailPath = public_path($course->thumbnail);
            if (file_exists($previousThumbnailPath)) {
                unlink($previousThumbnailPath);
            }
        }

        // Finally, delete the course
        $course->delete();

        return $this->success([], 'Course deleted successfully', 200);
    }

    public function submitForApproval($id)
    {
        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 200);
        }

        $course = Course::where('id', $id)->update([
            'status' => 'pending',
        ]);

        if (! $course) {
            return $this->error([], 'Course Not Found', 200);
        }

        return $this->success($course, 'Course submitted for approval successfully', 200);
    }

    public function deleteModule($id)
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User Not Found', 200);
        }


        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to delete modules', 200);
        }

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $data || ! $data->instructor) {
            return $this->error([], 'Instructor Not Found', 200);
        }

        $module = CourseModule::with('videos')->where('id', $id)->first();

        if (! $module) {
            return $this->error([], 'Module Not Found', 200);
        }

        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));
        // Delete all videos from Vimeo
        foreach ($module->videos as $video) {
            if (! empty($video->video_url)) {
                preg_match('/\/video\/(\d+)/', $video->video_url, $matches);
                if (! empty($matches[1])) {
                    $previousVideoId = $matches[1];
                    $vimeo->request("/videos/{$previousVideoId}", [], 'DELETE');
                }
            }
        }

        // Delete local video file
        if (! empty($module->file_url)) {
            $previousFilePath = public_path($module->file_url);
            if (file_exists($previousFilePath)) {
                unlink($previousFilePath);
            }
        }

        $module->delete();

        return $this->success([], 'Module deleted successfully', 200);
    }
}
