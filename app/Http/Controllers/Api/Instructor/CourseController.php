<?php
namespace App\Http\Controllers\Api\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseVideo;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Vimeo\Vimeo;

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
            'video_url.*'    => 'required|mimes:mp4,mov,ogg,qt,ogx,mkv,wmv,webm,flv,avi,ogv,ogg|max:1000000',
            'file_url'       => 'nullable|mimes:pdf,doc,docx|max:4096',
            'tags'           => 'nullable|array',
            'tags.*'         => 'nullable|string|max:255',
        ]);
       
        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 404);
        }

        $data = User::with('instructor')->where('role', 'instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }
        DB::beginTransaction();
       
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

        
        $fileurlName = $request->hasFile('file_url')
        ? uploadImage($request->file('file_url'), 'course/file')
        : null;

       
        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));

        $courseVideoEmbedUrls = [];

        foreach ($request->file('video_url', []) as $videoFile) {
            if ($videoFile->isValid()) {
                $allowedMimeTypes = ['video/mp4', 'video/quicktime', 'video/x-ms-wmv', 'video/x-msvideo'];
                if (!in_array($videoFile->getMimeType(), $allowedMimeTypes)) {
                    return $this->error([], "Unsupported video format", 422);
                }
                $courseVideoPath     = $videoFile->getPathname();
                $courseVideoResponse = $vimeo->upload($courseVideoPath, [
                    'name'        => $request->title,
                    'description' => $request->description,
                    'privacy'     => [
                        'view' => 'unlisted',
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

                $courseVideoData        = $vimeo->request($courseVideoResponse, [], 'GET')['body'];
                $courseVideoId          = trim($courseVideoData['uri'], '/videos/');
                $courseVideoEmbedUrls[] = "https://player.vimeo.com/video/" . $courseVideoId. "?dnt=1&autoplay=1&show_title=1&show_byline=1&show_portrait=1&color=00adef&related=0&controls=0&logo=0";
            }
        }

        
        foreach ($request->module_title as $key => $moduleTitle) {
            $module = CourseModule::create([
                'course_id'    => $course->id,
                'module_title' => $moduleTitle,
            ]);

           
            if (isset($courseVideoEmbedUrls[$key])) {
                CourseVideo::create([
                    'course_module_id' => $module->id,
                    'video_url'        => $courseVideoEmbedUrls[$key],
                    'file_url'         => $fileurlName,
                ]);
            }
        }

       
        foreach ($request->tags as $tag) {
            $course->tags()->attach($tag);
        }
        DB::commit();
        $course->load('category', 'tags', 'modules.videos');
        return $this->success($course, 'Course created successfully', 200);
        
    }

    public function getCourse(Request $request)
    {
        $user = auth()->user();

        $data = User::with('instructor')->where('id', $user->id)->first();

        if (! $user) {
            return $this->error([], 'User Not Found', 404);
        }

        $course = Course::with('instructor.user:id,first_name,last_name,role', 'category', 'tags', 'modules.videos')->where('instructor_id', $data->instructor->id);

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
        $course = $course->get();

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
            'video_url'      => 'nullable|array',
            'video_url.*'    => 'nullable|mimes:mp4,mov,ogg,qt,ogx,mkv,wmv,webm,flv,avi,ogv,ogg|max:1000000',
            'file_url'       => 'nullable|mimes:pdf,doc,docx|max:4096',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 404);
        }

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

        $vimeo = new Vimeo(env('VIMEO_CLIENT'), env('VIMEO_SECRET'), env('VIMEO_ACCESS'));

        $courseVideoEmbedUrls = [];

        foreach ($request->file('video_url', []) as $videoFile) {
            if ($videoFile->isValid()) {
                $allowedMimeTypes = ['video/mp4', 'video/quicktime', 'video/x-ms-wmv', 'video/x-msvideo'];
                if (!in_array($videoFile->getMimeType(), $allowedMimeTypes)) {
                    return $this->error([], "Unsupported video format", 422);
                }
                $courseVideoPath     = $videoFile->getPathname();
                $courseVideoResponse = $vimeo->upload($courseVideoPath, [
                    'name'        => $request->title,
                    'description' => $request->description,
                    'privacy'     => [
                        'view' => 'unlisted',
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

                $courseVideoData        = $vimeo->request($courseVideoResponse, [], 'GET')['body'];
                $courseVideoId          = trim($courseVideoData['uri'], '/videos/');
                $courseVideoEmbedUrls[] = "https://player.vimeo.com/video/" . $courseVideoId. "?dnt=1&autoplay=1&show_title=1&show_byline=1&show_portrait=1&color=00adef&related=0&controls=0&logo=0";
            }
        }

        // Modules & Videos
        foreach ($request->module_title as $key => $moduleTitle) {
            $module = CourseModule::updateOrCreate([
                'course_id'    => $course->id,
                'module_title' => $moduleTitle,
            ]);

            if (isset($courseVideoEmbedUrls[$key])) {
                CourseVideo::updateOrCreate([
                    'course_module_id' => $module->id,
                    'video_url'        => $courseVideoEmbedUrls[$key],
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

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 404);
        }

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

    public function submitForApproval($id)
    {
        $user = auth()->user();

        if ($user->status != "active") {
            return $this->error([], 'You don’t have permission to upload courses', 404);
        }

        $course = Course::where('id', $id)->update([
            'status' => 'pending',
        ]);

        if (! $course) {
            return $this->error([], 'Course Not Found', 404);
        }

        return $this->success($course, 'Course submitted for approval successfully', 200);
    }

}
