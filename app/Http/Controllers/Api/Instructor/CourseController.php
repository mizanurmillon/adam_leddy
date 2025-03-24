<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseVideo;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    use ApiResponse;

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'thumbnail'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'module_title'   => 'required|array',
            'module_title.*' => 'required|string|max:255',
            'video_url'      => 'required|array',
            'video_url.*'    => 'required|url',
            'file_url'       => 'nullable|mimes:pdf,doc,docx|max:4096',
            'tags'           => 'nullable|array',
            'tags.*'         => 'nullable|string|max:255',
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        // Thumbnail Upload
        $thumbnailName = $request->hasFile('thumbnail')
        ? uploadImage($request->file('thumbnail'), 'course')
        : null;

        // Course Create
        $course = Course::create([
            'instructor_id' => $user->instructor->id,
            'title'         => $request->title,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'thumbnail'     => $thumbnailName,
        ]);

        // File Upload (Optional)
        $fileurlName = $request->hasFile('file_url')
        ? uploadImage($request->file('file_url'), 'course/file')
        : null;

        // Modules and Videos
        foreach ($request->module_title as $key => $moduleTitle) {
            $module = CourseModule::create([
                'course_id'    => $course->id,
                'module_title' => $moduleTitle,
            ]);
        
            // Check if video URLs exist and properly formatted for each module
            $videoUrls = isset($request->video_url[$key]) && is_array($request->video_url[$key]) 
                         ? $request->video_url[$key] 
                         : (isset($request->video_url[$key]) ? [$request->video_url[$key]] : []);
        
            // Insert videos into database
            foreach ($videoUrls as $videoUrl) {
                CourseVideo::create([
                    'course_module_id' => $module->id,
                    'video_url'        => $videoUrl,
                    'file_url'         => $fileurlName, 
                ]);
            }
        }

        // Tags
        foreach ($request->tags as $tag) {
            $course->tags()->attach($tag);
        }

        $course->load('category', 'tags', 'modules.videos');
        return $this->success($course, 'Course created successfully', 200);
    }

    public function getCourse()
    {
        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $course = Course::with('instructor.user:id,first_name,last_name,role', 'category', 'tags', 'modules.videos')->where('instructor_id', $data->instructor->id)->get();

        if ($course->isEmpty()) {
            return $this->error([], 'Course Not Found', 404);
        }

        return $this->success($course, 'Course found successfully', 200);
    }

    public function getCourseDetails($id)
    {
        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $course = Course::with('instructor.user:id,first_name,last_name,role', 'category', 'tags', 'modules.videos')->where('instructor_id', $data->instructor->id)->where('id', $id)->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 404);
        }

        return $this->success($course, 'Course found successfully', 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'thumbnail'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'module_title'   => 'required|array',
            'module_title.*' => 'required|string|max:255',
            'video_url'      => 'required|array',
            'video_url.*'    => 'required|url',
            'file_url'       => 'nullable|mimes:pdf,doc,docx|max:4096',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $course = Course::with('modules.videos')->where('instructor_id', $data->instructor->id)->where('id', $id)->first();

        if (! $course) {
            return $this->error([], 'Course Not Found', 404);
        }

        // Thumbnail Upload
        $thumbnailName = $request->hasFile('thumbnail')
        ? uploadImage($request->file('thumbnail'), 'course')
        : $course->thumbnail;

        // File Upload (Optional)
        $fileurlName = $request->hasFile('file_url')
        ? uploadImage($request->file('file_url'), 'course/file')
        : $course->file_url;

        // Course Update
        $course->update([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'thumbnail'   => $thumbnailName,
        ]);

        // Modules & Videos
        foreach ($request->module_title as $key => $moduleTitle) {
            $module = CourseModule::create([
                'course_id'    => $course->id,
                'module_title' => $moduleTitle,
            ]);

            if (isset($request->video_url[$key])) {
                CourseVideo::create([
                    'course_module_id' => $module->id,
                    'video_url'        => $request->video_url[$key],
                    'file_url'         => $fileurlName,
                ]);
            }
        }

        $course->load('category', 'modules.videos');
        return $this->success($course, 'Course updated successfully', 200);
    }

    public function delete($id)
    {
        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $course = Course::with('modules.videos')->where('instructor_id', $data->instructor->id)->where('id', $id)->first();

        if ($course->thumbnail) {

            $previousThumbnailPath = public_path($course->thumbnail);

            if (file_exists($previousThumbnailPath)) {
                unlink($previousThumbnailPath);
            }
        }

        foreach ($course->modules as $module) {
            foreach ($module->videos as $video) {
                if ($video->file_url) {
                    $videoPath = public_path($video->file_url);
                    if (file_exists($videoPath)) {
                        unlink($videoPath);
                    }
                }
            }
        }

        if (! $course) {
            return $this->error([], 'Course Not Found', 404);
        }

        $course->delete();

        return $this->success([], 'Course deleted successfully', 200);
    }

}
